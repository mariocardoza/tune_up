<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Vehiculo;
use PDF;

class VehiculoController extends Controller
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

    public function info($id)
    {
        $retorno=Vehiculo::info($id);
        return $retorno;
    }

    public function placa(Request $r)
    {
        $cliente=[];
        $carro=[];
        $v=Vehiculo::where('placa',$r->placa)->first();
        if($v){
            $cliente=$v->cliente;
        }
        return array(1,$v,$cliente);
    }

    public function historial($placa)
    {
        $carro=Vehiculo::find($placa);
        
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('vehiculos.historial',compact('carro'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('historial.pdf');
    }

    public function index()
    {
        $vehiculos=Vehiculo::whereEstado(1)->orderby('cliente_id','asc')->get();
        return view('vehiculos.index',compact('vehiculos'));
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
            $vehiculo=Vehiculo::create([
                'cliente_id'=>$request->cliente_id,
                'placa'=>$request->placa,
                'anio'=>$request->anio,
                'marca_id'=>$request->marca_id,
                'modelo_id'=>$request->modelo_id,
                'tipomedida'=>$request->tipomedida,
                'motor' => $request->motor == "" ? 'N/A' : $request->motor,
                'vin' => $request->vin == "" ? 'N/A' : $request->vin,
                'notas'=>$request->notas
            ]);
            return array(1,"exito");
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
            $carro=Vehiculo::find($id);
            return array(1,"exito",$carro);
        }catch(Exception $e){
            return array(-1);
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
        try{
            $carro=Vehiculo::find($id);
            //$carro->fill($request->all());
            $carro->cliente_id=$request->cliente_id;
            $carro->placa=$request->placa,
            $carro->anio=$request->anio,
            $carro->marca_id=$request->marca_id,
            $carro->modelo_id=$request->modelo_id,
            $carro->tipomedida=$request->tipomedida,
            $carro->motor = $request->motor == "" ? 'N/A' : $request->motor,
            $caroo->vin = $request->vin == "" ? 'N/A' : $request->vin,
            $carro->notas=$request->notas
            $carro->save();
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
        try{
            $v=Vehiculo::find($id);
            $v->estado=2;
            $v->placa='';
            $v->save();
            return array(1,$v);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage);
        }

    }

    protected function validar(array $data)
    {
        $mensajes=array(
          'placa.required'=>'La placa es obligatoria',
          'cliente_id.required'=>'El cliente es obligatorio',
          'placa.unique'=>'La placa digitada ya existe en la base de datos',
          'marca_id.required'=>'La marca es obligatoria',
          'motor.required'=>'El número de motor es obligatorio',
          'motor.unique'=>'El número de motor ya existe en la base de datos',
      );
      return Validator::make($data, [
          'placa'=>'required|unique:vehiculos',
          'marca_id'=>'required',
          'motor'=>'unique:vehiculos',
          'cliente_id'=>'required',
      ],$mensajes);
    }
}
