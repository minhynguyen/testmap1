<style>
	#map-canvas{
		width: 800px;
		height: 300px;
		
	}
</style>

<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyB6K1CFUQ1RwVJ-nyXxd6W0rfiIBe12Q&libraries=places"
  type="text/javascript"></script>

<div class="container">
	<div class="col-sm-4">
		<h1>THÊM VỊ TRÍ</h1>
		<form name="frmChude" method="POST" action="{{route('location.store')}}">		
		{{ csrf_field() }}	
			<div class="form-group">
				<label for="">TÊN ĐỊA ĐIỂM</label>
				<input type="text" class="form-control input-sm" name="title">
			</div>

			<div class="form-group">
				<label for="">ĐIỂM CẦN THÊM:</label>
				<input type="text" id="searchmap" style="width: 600PX" placeholder="Nhập Tên">
				<hr>
				<div id="map-canvas"></div>
			</div>

			<div class="form-group">
				<label for="">VĨ ĐỘ (LATITUDE)</label>
				<input type="text" class="form-control input-sm" name="lat" id="lat">
			</div>

			<div class="form-group">
				<label for="">KINH ĐỘ (LONGITUDE)</label>
				<input type="text" class="form-control input-sm" name="lng" id="lng">
			</div>

			<button class="btn btn-sm btn-danger">LƯU</button>
	</div>

</div>

<script>


	var map = new google.maps.Map(document.getElementById('map-canvas'),{
		center:{
			lat: 10.031450,
        	lng: 105.768872
		},
		zoom:15
	});

	var marker = new google.maps.Marker({
		position: {
			lat: 10.031450,
        	lng: 105.768872
		},
		map: map,
		draggable: true
	});



	var searchBox = new google.maps.places.SearchBox(document.getElementById('searchmap'));

	google.maps.event.addListener(searchBox,'places_changed',function(){

		var places = searchBox.getPlaces();
		var bounds = new google.maps.LatLngBounds();
		var i, place;

		for(i=0; place=places[i];i++){
  			bounds.extend(place.geometry.location);
  			marker.setPosition(place.geometry.location); //set marker position new...
  		}

  		map.fitBounds(bounds);
  		map.setZoom(15);

	});

	google.maps.event.addListener(marker,'position_changed',function(){

		var lat = marker.getPosition().lat();
		var lng = marker.getPosition().lng();

		$('#lat').val(lat);
		$('#lng').val(lng);

	});

</script>