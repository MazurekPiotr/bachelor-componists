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
    var images = [];
    for (index = 0; index < response.length; ++index) {
        if (response[index - 1] != null) {
            if (response[index - 1].id === response[index].id) {
            }
        }
        else {
            images.push(response[index]);
        }
    }
    var countries = [];
    for (index = 0; index < response.length; ++index) {
        var valuesToPush = [];
        valuesToPush["id"] = response[index].country;
        valuesToPush[1] = response[index].country;
        countries.push(valuesToPush);
    }

    var map = AmCharts.makeChart( "chartdiv", {
        "type": "map",
        "theme": "light",
        "projection": "miller",
        dragMap: true,
        zoomOnDoubleClick: true,

        zoomControl: {
            zoomControlEnabled: true,
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
            "images": images,
            "areas" : countries
        }
    } );

    var zoomToAreasIds = [];
    for(var i = 0; i < countries.length; i++) {
        zoomToAreasIds.push(countries[i].id);
    }
    var zoomToAreas = [];
    var area;
    for(var i = 0; i < zoomToAreasIds.length; i++) {
        if (area = map.getObjectById(zoomToAreasIds[i]))
            zoomToAreas.push(area);
    }
    console.log(zoomToAreas);
    map.zoomToGroup(zoomToAreas);

// add events to recalculate map position when the map is moved or zoomed
    map.addListener( "positionChanged", updateCustomMarkers );

// this function will take current images on the map and create HTML elements for them
    function updateCustomMarkers( event ) {
        // get map object
        var map = event.chart;

        // go through all of the images
        for ( var x in map.dataProvider.images ) {
            // get MapImage object
            var image = map.dataProvider.images[ x ];

            // check if it has corresponding HTML element
            if ( 'undefined' == typeof image.externalElement )
                image.externalElement = createCustomMarker( image );

            // reposition the element accoridng to coordinates
            var xy = map.coordinatesToStageXY( image.longitude, image.latitude );
            image.externalElement.style.top = xy.y + 'px';
            image.externalElement.style.left = xy.x + 'px';
        }
    }

// this function creates and returns a new marker element
    function createCustomMarker( image ) {
        // create holder
        var holder = document.createElement( 'div' );
        holder.className = 'map-marker';
        holder.title = image.title;
        holder.style.position = 'absolute';

        // maybe add a link to it?
        if ( undefined != image.url ) {
            holder.onclick = function() {
                window.location.href = image.url;
            };
            holder.className += ' map-clickable';
        }

        // append the marker to the map container
        image.chart.chartDiv.appendChild( holder );

        return holder;
    }
}