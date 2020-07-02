<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cotizacione;
use App\Taller;

class CreditoController extends Controller
{
    // contructor
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte($id)
    {
        $cotizacion=Cotizacione::find($id);
        $taller=Taller::find(1);
        $pdf = \PDF::loadView('creditos.reporte',compact('cotizacion','taller'));
        $customPaper = array(0,0,360,360);
        //$pdf->setPaper($customPaper);
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('factura.pdf');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $correlativo=Cotizacione::correlativo(3);
        $clientes=Cliente::where('estado',1)->get();
        return view('creditos.create',compact('clientes','correlativo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if($id!=0){
            $siguiente=$anterior=0;
            $cotizacion=Cotizacione::findorFail($id);
            $s= Cotizacione::where('id','>', intval($id))->where('tipo_documento',3)->orderBy('id', 'asc')->first();
            $a= Cotizacione::where('id','<', intval($id))->where('tipo_documento',3)->orderBy('id', 'desc')->first();
            $clientes=Cliente::where('estado',1)->get();
            
            if($s != null){
                $siguiente=$s->id;
            }
            if($a != null){
                $anterior=$a->id;
            }

            $correlativo=Cotizacione::correlativo(3);
            
            return view('creditos.show',compact('cotizacion','clientes','siguiente','anterior','correlativo'));
        }else{
            $clientes=Cliente::where('estado',1)->get();
            return redirect('creditos/create')->with('clientes');
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

    public function clonar(Request $request)
    {
        $coti=Cotizacione::find($request->id);
        try{
            $retorno=Cotizacione::clonar($coti->id,$request->tipo_documento,$request->fecha);
            if($request->tipo_documento==2){
                $ruta="../facturas/".$retorno[2];
            }else if($request->tipo_documento==3){
                $ruta="../creditos/".$retorno[2];
            }else{
                $ruta="../exportaciones/".$retorno[2];
            }
            return array(1,$retorno,$ruta);
        }catch(Excetion $e){
            return array(-1,"error",$e->getMessage());
        }
    }

    public function facturar_a(Request $request)
    {
        try{
            $coti=Cotizacione::find($request->cotizacion_id);
            $coti->facturar_a=$request->facturar_a;
            $coti->imprimir_veh=$request->imprimir_veh;
            $coti->save();
            return array(1);

        }catch(Excetion $e){
            return array(-1,"error",$e->getMessage());
        }
    }

    public function cancelarfacturar(Request $request)
    {
        try{
            $coti=Cotizacione::find($request->cotizacion_id);
            $coti->facturar_a=null;
            $coti->imprimir_veh='si';
            $coti->save();
            return array(1);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
    }

    public function imprimir_veh(Request $request)
    {
        try{
            $coti=Cotizacione::find($request->cotizacion_id);
            $coti->imprimir_veh=$request->valor;
            $coti->save();
            return array(1);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
    }
}
