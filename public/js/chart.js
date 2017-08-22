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
    var users = response;
    var countries = [];
    for (index = 0; index < response.length; ++index) {
        var valuesToPush = [];
        valuesToPush["id"] = response[index].country;
        valuesToPush["customData"] = response[index].title;
        valuesToPush["selectable"] = true;
        valuesToPush["user_id"] = response[index].id;
        countries.push(valuesToPush);
    }

    var map = AmCharts.makeChart( "chartdiv", {
        "type": "map",
        "theme": "light",
        "projection": "miller",
        dragMap: false,

        zoomControl: {
            zoomControlEnabled: false,
            panControlEnabled: false
        },
        responsive: {
            "enabled": true
        },

        areasSettings: {
            "unlistedAreasColor": "#d8a911",
            "autoZoom" : false,
        },
        dataProvider: {
            "map": "worldLow",
            "areas" : countries
        },
    } );

    map.addListener("rendered", function (event) {

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
    });

    map.addListener("rollOverMapObject", function(event) {
        for(i = 0; i < users.length; i++) {
            if(event.mapObject.id == users[i].country) {
                $('#user-' + users[i].id).css('height', '220px');
            }
        }
    });

    map.addListener("rollOutMapObject", function(event) {
        for(i = 0; i < users.length; i++) {
            if(event.mapObject.id == users[i].country) {
                $('#user-' + users[i].id).css('height', '200px');
            }
        }
    });
    map.validateNow();
}

