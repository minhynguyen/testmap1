
<?php

	function GetDrivingDistance($lat1, $lat2, $long1, $long2)
{
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".$lat1.",".$long1."&destinations=".$lat2.",".$long2."&mode=driving&language=pl-PL";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);
    $response_a = json_decode($response, true);
    $dist = $response_a['rows'][0]['elements'][0]['distance']['text'];
    $time = $response_a['rows'][0]['elements'][0]['duration']['text'];

    return array('distance' => $dist, 'time' => $time);
}
    $dist = GetDrivingDistance('{{$dsLo1->lat}}', '{{$dsLo2->lat}}', '{{$dsLo1->lng}}', '{{$dsLo2->lng}}');
    echo 'Distance: <b>'.$dist['distance'].'</b><br>Travel time duration: <b>'.$dist['time'].'</b>';
  ?>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCyB6K1CFUQ1RwVJ-nyXxd6W0rfiIBe12Q"
  type="text/javascript"></script>

<div class="container">

  	 <div>
        <strong>Start: </strong>
        <select id="start" onChange="GetDrivingDistance();">
        	@foreach ($dsLo as $dsLo1)
            <option value="{{$dsLo1->id}}">{{$dsLo1->title}}</option>
            @endforeach
        </select>
        <strong>End: </strong>
        <select id="start" onChange="GetDrivingDistance();">
        	@foreach ($dsLo as $dsLo2)
            <option value="{{$dsLo2->id}}">{{$dsLo2->title}}</option>
            @endforeach
        </select>
        
    </div>
    </div>
  










