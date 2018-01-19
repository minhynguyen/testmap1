<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$config = array();
    $config['center'] = 'Dai Hoc Can Tho, Can Tho';
    $config['zoom'] = '16';
    $config['map_height'] = '500';
    $config['geocodeCaching'] = true;
    $config['scrollwhell'] = false;

    GMaps::initialize($config);

    // danh dau

    $marker['position'] = 'Dai Hoc Can Tho, Can Tho';
    $marker['infowindow_content'] = 'Dai Hoc Can Tho';
    $marker['icon'] = 'https://chart.googleapis.com/chart?chst=d_map_spin&chld=1.5|0|00ff80|13|b|HO';

    GMaps::add_marker($marker);

    $circle['center'] = 'Dai Hoc Can Tho, Can Tho';
	$circle['radius'] = '100';
	GMaps::add_circle($circle);



    $marker['position'] = 'Sense City, Can Tho';
    $marker['infowindow_content'] = 'Dai Hoc Can Tho';
    $marker['icon'] = 'http://maps.google.com/mapfiles/ms/micons/green.png';

    GMaps::add_marker($marker);
    $map = GMaps::create_map();

    
    return view('welcome')->with('map', $map);
});
Route::get('/chiduong', function () {
	$config = array();
    $config['center'] = 'Dai Hoc Can Tho, Can Tho';
    $config['zoom'] = '14';
    $config['map_height'] = '500';
    $config['geocodeCaching'] = true;
    $config['scrollwhell'] = false;

    $config['directions'] = TRUE;
	$config['directionsStart'] = 'Dai Hoc Can Tho, Can Tho';
	$config['directionsEnd'] = 'Pham Ngu Lao, Can Tho';
	$config['directionsDivID'] = 'directionsDiv';

    GMaps::initialize($config);

    
    $map = GMaps::create_map();

    
    return view('welcome')->with('map', $map);
});

Route::get('location/add', function()
{
    //view
    return View::make('add');
});

// Route::post('vendor/add', function(){

//     Vendor::create(Input::all());

//     var_dump('vendor is added....');

// });
use App\location;
Route::get('location/{id}',function($id){

    $location = location::find($id);
    return View::make('location',compact('location'));

});
// Route::resource('vendor', 'vendorController');
Route::resource('location', 'LocationController');
Route::get('direction',function(){
    return View::make('direction');

});