<style>
	#map-canvas{
		width: 100%;
		height: 570px;
	}
</style>

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyB6K1CFUQ1RwVJ-nyXxd6W0rfiIBe12Q"
  type="text/javascript"></script>
<h1>Các Trường Đại Học</h1>

  <div id="map-canvas"></div>
<div class="container">
  
  
</div>

<script>
  var map = new google.maps.Map(document.getElementById('map-canvas'),{
    center:{
      lat: 10.0309641000,
      lng: 105.7689041000,
      
    },
    zoom: 15,
    zoomControl: false,
    streetViewControl: false,
    scrolwheel : true,
  });



@foreach ($dsLo as $dsLo)
  var lat = {{$dsLo->lat}};
  var lng = {{$dsLo->lng}};



  var marker = new google.maps.Marker({
    position:{
      lat:lat,
      lng: lng
    },
    map:map,

    icon: 'http://maps.google.com/mapfiles/ms/micons/green.png'
    
  });
  
  // geolocate();
  var customMapType = new google.maps.StyledMapType([
          {stylers: [{hue: '#00FF00'}]},
          {
                featureType: 'water',
                stylers: [{color: '#0033FF'}]
              },
              {
                featureType: 'road',
                elementType: 'geometry',
                stylers: [{color: '#f5f1e6'}]
              },
              {
                featureType: 'road.arterial',
                elementType: 'geometry',
                stylers: [{color: '#fdfcf8'}]
              },

        ]);
  var customMapTypeId ='custom_style'
        map.mapTypes.set(customMapTypeId, customMapType);
        map.setMapTypeId(customMapTypeId);
  var infowindow = new google.maps.InfoWindow();  
        google.maps.event.addListener(marker, 'click', (function(marker) {  
           return function() {  
               var content = '<p>{{$dsLo->title}}</p>{{$dsLo->lat}}' + ',' + '{{$dsLo->lng}}';  
               infowindow.setContent(content);  
               infowindow.open(map, marker);  
           }  
         })(marker));

@endforeach
var mylocate = new google.maps.Marker({
    position:{
      lat:lat,
      lng: lng
    },
    map:map,
  });
var infowindow = new google.maps.InfoWindow();  
        google.maps.event.addListener(mylocate, 'click', (function(mylocate) {  
           return function() {  
               var content = '<p>My locate: </p>' + lat + ', '+ lng;  
               infowindow.setContent(content);  
               infowindow.open(map, mylocate);  
           }  
         })(mylocate));
geolocate();
function GeolocationControl(){
  var geoButton = document.getElementById('curent-location');
  google.maps.event.addListener(geoButton, 'click', geolocate); 
};
function geolocate(){
  if (navigator.geolocation) { //nếu trình duyệt lấy đc vị trí
          navigator.geolocation.getCurrentPosition(function(position) {

            // console.log(position);
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };


            map.setCenter(pos);
            mylocate.setPosition(pos);

          });
        }else{
          alert('use location');
        }
  };
  
  GeolocationControl();
  // geolocate();


</script>