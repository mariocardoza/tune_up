<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cotizacione;

class CreditoController extends Controller
{
    // contructor
    public function __construct()
    {
        $this->middleware('auth');
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
            
            return view('creditos.show',compact('cotizacion','clientes','siguiente','anterior'));
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
}
