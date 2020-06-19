<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    protected $guarded = [];
    public function cliente()
    {
    	return $this->belongsTo('App\Cliente')->withDefault();
    }

    public function marca()
    {
    	return $this->belongsTo('App\Marca');
    }

    public function modelo()
    {
    	return $this->belongsTo('App\Modelo');
    }

    public function cotizaciones()
    {
        return $this->hasMany('App\Cotizacione')->orderBy('created_at','DESC');
    }

    public function cotis_activas()
    {
        return $this->hasMany('App\Cotizacione')->where('estado',1)->orderBy('created_at','DESC');
    }

    public static function info($id)
    {
        $html=$kms="";
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
        if($vehiculo->tipomedida=='km'):
        $kms='<div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label">Km Recepción</label>
                    <input type="number" name="kilometraje" class="form-control kilometraje kimi" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label">Km próxima</label>
                    <input type="number" name="km_proxima" class="form-control kmproxi kimiproxi" readonly>
                </div>
            </div>';
        else:
            $kms='<div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label">Mi Recepción</label>
                    <input type="number" name="kilometraje" class="form-control kilometraje millaje" >
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="" class="control-label">Mi próxima</label>
                    <input type="number" name="km_proxima" class="form-control kmproxi miproxi" readonly>
                </div>
            </div>';
        endif;
        return array(1,"exito",$html,floatval($vehiculo->kilometraje),$kms);
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
