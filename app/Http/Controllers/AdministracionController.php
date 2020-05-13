<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Porcentaje;
use App\Taller;

class AdministracionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $porcentajes=Porcentaje::where('estado',1)->get();
        $taller=Taller::find(1);
        return view('administracion.create',compact('porcentajes','taller'));
    }

    public function porcentaje(Request $request)
    {
        try{
            $porcentaje=Porcentaje::find($request->id);
            $porcentaje->porcentaje=$request->porcentaje;
            $porcentaje->save();

            $porcentajes=Porcentaje::all();
            foreach($porcentajes as $p){
                session([$p->nombre_simple => $p->porcentaje/100]);
            }
            return array(1,"exito");
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
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
        try{
            $t=Taller::find($id);
            $t->fill($request->all());
            $t->save();
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
    public function destroy($id)
    {
        //
    }
}
