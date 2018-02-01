<style>
	#map-canvas{
		width: 100%;
    /*width: 1500px;*/
		height: 550px;
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
      lng: 105.7689041000
    },
    zoom: 15
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
  var infowindow = new google.maps.InfoWindow();  
        google.maps.event.addListener(marker, 'click', (function(marker) {  
           return function() {  
               var content = '<p>{{$dsLo->title}}</p>';  
               infowindow.setContent(content);  
               infowindow.open(map, marker);  
           }  
         })(marker)); 
 
@endforeach


</script>