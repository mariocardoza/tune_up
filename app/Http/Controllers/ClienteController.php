<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;
use App\Vehiculo;
use App\Marca;
use Validator;
use Session;

class ClienteController extends Controller
{
    // contructor
    public function __construct()
    {
        $this->middleware('auth');
        session()->forget('pruebita');
        $porcentajes=\App\Porcentaje::all();
        foreach($porcentajes as $p){
            session([$p->nombre_simple => $p->porcentaje/100]);
        }
        session(['pruebita'=>date("H:i:s")]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function obtenerModelos($id)
    {
        $marcas=Marca::find($id);
        $html='';
        $html.='<option value="">Seleccione un modelo</option>';
        foreach ($marcas->modelo as $m) {
            $html.='<option value="'.$m->id.'">'.$m->nombre.'</option>';
        }
        return array(1,"exito",$html);
    }

    public function index()
    {
        $clientes=Cliente::whereEstado(1)->orderBy('id','ASC')->get();
        return view('clientes.index',compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validar($request->all())->validate();
        try{
            $cliente=Cliente::create($request->All());
            return array(1,"exito",$cliente->id);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
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
        $cliente=Cliente::findorFail($id);
        
        return view('clientes.show',compact('cliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $modal=Cliente::modal_editar($id);
        return $modal;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cliente $cliente)
    {
        try{
            $cliente->fill($request->all());
            $cliente->save();
            return array(1,"exito");
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $c=Cliente::find($id);
            $c->estado=2;
            $c->save();
            return array(1);
        }catch(Exception $e){
            return array(-1,"err",$e->getMessage());
        }
    }

    protected function validar(array $data)
    {
        $mensajes=array(
          'tipo.required'=>'El tipo de cliente es obligatorio',
          'nombre.required'=>'El nombre del cliente es obligatorio',

      );
      return Validator::make($data, [
          'tipo'=>'required',
          'nombre'=>'required',
      ],$mensajes);
    }
}
