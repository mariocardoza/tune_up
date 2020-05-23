<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cotizacione;
use PDF;

class FacturaController extends Controller
{
    // contructor
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function reporte($id)
    {
        $factura=Cotizacione::find($id);
        $pdf = \PDF::loadView('facturas.reporte',compact('factura'));
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
        $clientes=Cliente::where('estado',1)->get();
        $uf=0;
        $ultimafactura=Cotizacione::where('tipo_documento',2)->get();
        if(count($ultimafactura)>0) { $uf=$ultimafactura->last()->id;}
        return view('facturas.create',compact('clientes','uf'));
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
            $s= Cotizacione::where('id','>', intval($id))->where('tipo_documento',2)->orderBy('id', 'asc')->first();
            $a= Cotizacione::where('id','<', intval($id))->where('tipo_documento',2)->orderBy('id', 'desc')->first();
            $clientes=Cliente::where('estado',1)->get();
            
            if($s != null){
                $siguiente=$s->id;
            }
            if($a != null){
                $anterior=$a->id;
            }
            
            return view('facturas.show',compact('cotizacion','clientes','siguiente','anterior'));
        }else{
            $clientes=Cliente::where('estado',1)->get();
            return redirect('facturas/create')->with('clientes');
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
}
