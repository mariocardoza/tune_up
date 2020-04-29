<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $guarded = [];

    public function modelo()
    {
    	return $this->hasMany('App\Modelo');
    }
}
