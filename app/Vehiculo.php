<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $guarded = [];
    public function cliente()
    {
    	return $this->belongsTo('App\Cliente');
    }

    public function marca()
    {
    	return $this->belongsTo('App\Marca');
    }

    public function modelo()
    {
    	return $this->belongsTo('App\Modelo');
    }

    public static function info($id)
    {
        $html="";
        $vehiculo=Vehiculo::find($id);
        $html.='<div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Marca: '.$vehiculo->marca->marca.'</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Modelo: '.$vehiculo->modelo->nombre.'</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">Año: '.$vehiculo->anio.'</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">N° motor: '.$vehiculo->motor.'</label>
                    </div>
                </div>';
        return array(1,"exito",$html,floatval($vehiculo->kilometraje));
    }

    public static function obtenervehiculos($id,$actual)
    {
        $vehiculo=Cliente::find($id);
        $html='<option value="">Seleccione</option>';
        foreach ($vehiculo->vehiculo as $v) {
            if($actual==$v->id):
            $html.='<option selected value="'.$v->id.'">'.$v->placa.'</option>';
            else:
                $html.='<option value="'.$v->id.'">'.$v->placa.'</option>';
            endif;
        }
        return array(1,"exito",$html);
    }
}
