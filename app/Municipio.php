<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    public function departamento()
    {
        // Un municipio pertenece a un departamento
        return $this->belongsTo('App\Departamento', 'departamento_codigo', 'codigo');
    }
}
