<?php
//including the database connection file
include_once("./vendor/crud-php-simple/config.php");

//fetching data in descending order (lastest entry first)
//$result = mysql_query("SELECT * FROM users ORDER BY id DESC"); // mysql_query is deprecated
$result = mysqli_query($mysqli, "SELECT * FROM users ORDER BY id DESC"); // using mysqli_query instead
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />

        <title>CUSC Map</title>

        <link rel="stylesheet" href="vendor/leaflet/leaflet.css" type="text/css">
        <link href="vendor/leaflet-3rd/leaflet.pm/dist/leaflet.pm.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="css/main.css" type="text/css">

        <script src="vendor/jquery/jquery.js" type="text/javascript"></script>
        <script src="vendor/leaflet/leaflet.js" type="text/javascript"></script>
        <script src="vendor/leaflet-3rd/leaflet.pm/dist/leaflet.pm.min.js" type="text/javascript"></script>
        
        <link rel="stylesheet" href="vendor/leaflet-3rd/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.css" />
        <script src="vendor/leaflet-3rd/Leaflet.PolylineMeasure/Leaflet.PolylineMeasure.js"></script>

        <style>
            #mapid { height: 500px; }
        </style>
    </head>
    <body>
        <h2>Demo CUSC Map</h2>
        <div id="mapid"></div>
        <script>
            var mymap = L.map('mapid').setView([10, 105], 13);
            attribution = mymap.attributionControl;
            attribution.setPrefix('Hello World');

            L.control.scale().addTo(mymap);

            L.control.polylineMeasure(options).addTo(mymap);

            // define toolbar options
            var options = {
                position: 'topleft', // toolbar position, options are 'topleft', 'topright', 'bottomleft', 'bottomright'
                drawMarker: true, // adds button to draw markers
                drawPolygon: true, // adds button to draw a polygon
                drawPolyline: true, // adds button to draw a polyline
                drawCircle: true, // adds button to draw a cricle
                editPolygon: true, // adds button to toggle global edit mode
                deleteLayer: true   // adds a button to delete layers
            };

            // add leaflet.pm controls to the map
            mymap.pm.addControls(options);

            /*
             var baseLayers = {
             "Mapbox": mapbox,
             "OpenStreetMap": osm
             };
             var overlays = {
             "Marker": marker,
             "Roads": roadsLayer
             };
             L.control.layers(baseLayers, overlays).addTo(mymap);
             */

            var littleton = L.marker([39.61, -105.02]).bindPopup('This is Littleton, CO.'),
                    denver = L.marker([39.74, -104.99]).bindPopup('This is Denver, CO.'),
                    aurora = L.marker([39.73, -104.8]).bindPopup('This is Aurora, CO.'),
                    golden = L.marker([39.77, -105.23]).bindPopup('This is Golden, CO.');

            var cities = L.layerGroup([littleton, denver, aurora, golden]);



            var baseMap = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
                attribution: '',
                maxZoom: 18,
                id: 'mapbox.streets',
                accessToken: 'your.mapbox.access.token'
            });

            L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(mymap);


            var marker = L.marker([51.5, -0.09]).addTo(mymap);

            var circle = L.circle([51.508, -0.11], {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: 500
            }).addTo(mymap);

            var polygon = L.polygon([
                [51.509, -0.08],
                [51.503, -0.06],
                [51.51, -0.047]
            ]).addTo(mymap);

            var baseMaps = {
                "BaseMap": baseMap
            };

            var overlayMapCities = {
                "Cities": cities
            };

            var overlayMapCircle = {
                "Circle": circle
            };

            L.control.layers(baseMaps, overlayMapCities, overlayMapCircle).addTo(mymap);

            marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
            circle.bindPopup("I am a circle.");
            polygon.bindPopup("I am a polygon.");

            var popup = L.popup()
                    .setLatLng([51.5, -0.09])
                    .setContent("I am a standalone popup.")
                    .openOn(mymap);
            
            // listen to when a new layer is created
            mymap.on('pm:create', function(e) {
                console.log(e);
            
    });
        </script>
    </body>
</html>