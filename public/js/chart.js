/**
 * Created by piotr on 14/06/2017.
 */

var id = document.getElementById('projectId').dataset.projectId;

if(id !== 'all') {
    $.get('/api/getUserData/' + id, function (response) {
        generate(response);
    });
}
else {
    $.get('/api/getUserData/', function (response) {
        generate(response);
    });
}

function generate(response) {
    var countries = [];
    console.log(response);
    for (index = 0; index < response.length; ++index) {
        var valuesToPush = [];
        valuesToPush["id"] = response[index].country;
        valuesToPush["customData"] = response[index].title;
        valuesToPush["selectable"] = true;
        valuesToPush["modalUrl"] = "http://localhost:8888/user/profile/@rat";
        countries.push(valuesToPush);
    }

    map = AmCharts.makeChart( "chartdiv", {
        "type": "map",
        "theme": "light",
        "projection": "miller",
        dragMap: true,

        zoomControl: {
            zoomControlEnabled: false,
            panControlEnabled: false
        },

        "imagesSettings": {
            "rollOverScale": 5,
            "selectedScale": 3,
            "selectedColor": "#d8a911",
            "color": "#FFF9C6",
        },
        "responsive": {
            "enabled": true
        },

        "areasSettings": {
            "unlistedAreasColor": "#d8a911",
            "autoZoom" : true,
        },
        "dataProvider": {
            "map": "worldLow",
            "areas" : countries
        },
        "listeners": [{
            "event": "clickMapObject",
            "method": function(event) {
                $.fancybox({
                    "href": event.mapObject.modalUrl,
                    "title": event.mapObject.title,
                    "type": "iframe"
                });
            }
        }]
    } );

    var zoomToAreasIds = [];

    for(var i = 0; i < countries.length; i++) {
        zoomToAreasIds.push(countries[i].id);
    }

    var zoomToAreas = [];
    var area;
    for(var i = 0; i < zoomToAreasIds.length; i++) {
        if (area = map.getObjectById(zoomToAreasIds[i])){
            zoomToAreas.push(area);
        }
    }

    map.zoomToGroup(zoomToAreas);

}

