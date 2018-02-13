<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class location extends Model
{
	protected $table = 'location';
    protected $guarded = ['id'];
    protected $primaryKey = 'id';
    protected $fillable = ['title','lat','lng'];
}
// public static function scopeGetByDistance($query,$lat, $lng, $distance)
// {
//   $results = DB::select(DB::raw('SELECT id, ( 3959 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(lat) ) ) ) AS distance FROM location HAVING distance < ' . $distance . ' ORDER BY distance') );

//     if(!empty($results)) {

//         $ids = [];

//         //Extract the id's
//         foreach($results as $q) {
//            array_push($ids, $q->id);
//         }

//         return $query->whereIn('id',$ids);

//     }

//     return $query;

//  }