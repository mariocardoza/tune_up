<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Marca;

class MarcaController extends Controller
{
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
        $marcas=Marca::whereEstado(1)->get();
        return view('marcas.index',compact('marcas'));
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
            $marca=Marca::create($request->All());
            return array(1,"exito",$marca);
        }catch(Exception $e){
            return array(-1,"eror",$e->getMessage());
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
          $trabajo=Marca::find($id);
          return array(1,"exito",$trabajo);
        }catch(Exception $e){
          return array(-1,"error",$e->getMessage());
        }
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
         $this->validar($request->all())->validate();
        try{
          $r=Marca::find($id);
          $r->fill($request->all());
          $r->save();
          return array(1);
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
    public function destroy($id,Request $r)
    {
        try{
          $t=Marca::find($id);
          if($r->borrar==1):
          $t->estado=2;
          else:
          $t->estado=1;
          endif;
          $t->save();
          
          return array(1);
        }catch(Exception $e){
          return array(-1,"error",$e->getMessage());
        }
    }

    protected function validar(array $data)
    {
        $mensajes=array(
          'marca.required'=>'El nombre de la marca es obligatorio',
        
      );
      return Validator::make($data, [
          'marca'=>'required',
      ],$mensajes);
    }
}
