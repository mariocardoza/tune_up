<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cotizacione;
use App\Cliente;
use App\Vehiculo;
use App\RepuestoPrevia;
use App\TrabajoPrevia;
use App\RepuestoDetalle;
use App\TrabajoDetalle;
use App\Taller;
use DB;
use PDF;

class CotizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function obtenerprevia($id)
    {
        $retorno=Cotizacione::obtenerprevias($id);
        return $retorno;
    }

    public function obtenerguardadas($id)
    {
        $retorno=Cotizacione::obtenerguardadas($id);
        return $retorno;
    }

    public function obtenervehiculos($id,Request $request)
    {
        $retorno=Vehiculo::obtenervehiculos($id,$request->actual);
        return $retorno;
    }
    public function index()
    {
        $cotizaciones=Cotizacione::where('tipo_documento',1)->get();
        return view('cotizaciones.index',compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clientes=Cliente::where('estado',1)->get();
        return view('cotizaciones.create',compact('clientes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $total=0.0;
            DB::beginTransaction();
                       
            $cotizacion=Cotizacione::create([
            'vehiculo_id'=>$request->vehiculo_id,
            'cliente_id'=>$request->cliente_id,
            'tipo_documento'=>1,
            'n_impresiones'=>1,
            'fecha'=>invertir_fecha($request->fecha),
            'iva'=>$request->iva,
            'subtotal'=>$request->subtotal,
            'total'=>$request->total,
            'coniva'=>$request->eliva,
            ]);
            
             $trabajos_p=TrabajoPrevia::all();
            foreach ($trabajos_p as $t) {
                $t_detalle=TrabajoDetalle::create([
                    'trabajo_id'=>$t->trabajo_id,
                    'precio'=>$t->precio,
                    'cantidad'=>$t->cantidad,
                    'cotizacion_id'=>$cotizacion->id
                ]);
                $total=$total+($t->precio*$t->cantidad);
            }
            $repuestos_p=RepuestoPrevia::all();
            foreach ($repuestos_p as $r) {
                $r_detalle=RepuestoDetalle::create([
                    'repuesto_id'=>$r->repuesto_id,
                    'precio'=>$r->precio,
                    'cantidad'=>$r->cantidad,
                    'cotizacion_id'=>$cotizacion->id
                ]);
                $total=$total+($r->precio*$r->cantidad);
            }
           
            $vehiculo=Vehiculo::find($request->vehiculo_id);
            $vehiculo->kilometraje=$request->kilometraje;
            $vehiculo->km_proxima=$request->km_proxima;
            $vehiculo->save();

            if($request->eliva='si'){
                $eliva=$total*session('iva');
                $cotizacion->subtotal=$total;
                $cotizacion->iva=$eliva;
                $cotizacion->total=($eliva+$total);
                $cotizacion->save();
            }

            DB::commit();
            RepuestoPrevia::truncate();
            TrabajoPrevia::truncate();
            return array(1,"exito",$cotizacion->id);
        }catch(Exception $e){
            DB::rollBack();
            return array(-1,"error",$e->getMessage(),$total);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id!=0)
        {
          $siguiente=$anterior=0;
            $cotizacion=Cotizacione::findorFail($id);
            $s= Cotizacione::where('id','>', intval($id))->where('tipo_documento',1)->orderBy('id', 'asc')->first();
            $a= Cotizacione::where('id','<', intval($id))->where('tipo_documento',1)->orderBy('id', 'desc')->first();
            $clientes=Cliente::where('estado',1)->get();
            
            if($s != null){
                $siguiente=$s->id;
            }
            if($a != null){
                $anterior=$a->id;
            }
            
            return view('cotizaciones.show',compact('cotizacion','clientes','siguiente','anterior'));  
        }else{
            $clientes=Cliente::where('estado',1)->get();
            return redirect('cotizaciones/create')->with('clientes');
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function ivaventas(Request $r)
    {
        $fecha1=invertir_fecha($r->fecha1);
        $fecha2=invertir_fecha($r->fecha2);
        $cotizaciones=Cotizacione::where('fecha','>=',$fecha1)->where('fecha','<=',$fecha2)->get();
        $f1=$r->fecha1;
        $f2=$r->fecha2;
        //dd($cotizaciones);
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('cotizaciones.ivaventas',compact('cotizaciones','f1','f2'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('iva.pdf');
    }

    public function pdf($id)
    {
        $cotizacion=Cotizacione::find($id);
        $taller=Taller::find(1);
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('cotizaciones.prueba',compact('cotizacion','taller'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('cotizacion.pdf');
    }
}
