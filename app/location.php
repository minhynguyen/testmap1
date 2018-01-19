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
