<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Porcentaje extends Model
{
    protected $guarded = [];

    public static function retornar_porcentaje($dato)
    {
    	$porcentajes=Porcentaje::where('nombre_simple',$dato)->first();
    	$valor=0;
    	$valor=$porcentajes->porcentaje/100;
    	return $valor;
    }
}
