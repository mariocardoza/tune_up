<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Porcentaje;
use App\Cotizacione;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $porcentajes=Porcentaje::all();
            foreach($porcentajes as $p){
                session([$p->nombre_simple => $p->porcentaje/100]);
            }
        return view('home');
    }

    public function buscar(Request $request)
    {
        $ruta='';
        $retorno = Cotizacione::where('tipo_documento',$request->tipo_documento)->where('correlativo',$request->correlativo)->first();
        if($retorno->count() > 0){
            if($retorno->tipo_documento==1){
                $ruta='/cotizaciones/'.$retorno->id;
            }
            if($retorno->tipo_documento==2){
                $ruta='/facturas/'.$retorno->id;
            }
            if($retorno->tipo_documento==3){
                $ruta='/creditos/'.$retorno->id;
            }
            if($retorno->tipo_documento==4){
                $ruta='/exportaciones/'.$retorno->id;
            }
        }
        return array(1,$retorno,$ruta);
    }
}
