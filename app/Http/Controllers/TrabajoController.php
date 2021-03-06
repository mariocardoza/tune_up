<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trabajo;
use App\TrabajoPrevia;
use App\TrabajoDetalle;
use App\Cotizacione;
use App\Vehiculo;
use Validator;
use DB;

class TrabajoController extends Controller
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
        $trabajos=Trabajo::get();
        return view('trabajos.index',compact('trabajos'));
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
          if($request->vehiculo_id!=''):
            DB::beginTransaction();
            $trabajo=Trabajo::create([
                'nombre'=>mb_strtoupper($request->nombre),
                'codigo'=>$request->codigo,
                'precio'=>$request->precio
            ]);
            if($request->cotizacion_id==0):
                $coti=Cotizacione::create([
                  'vehiculo_id'=>$request->vehiculo_id,
                  'cliente_id'=>$request->cliente_id,
                  'tipo_documento'=>$request->tipo_documento,
                  'n_impresiones'=>0,
                  'fecha'=>invertir_fecha($request->fecha),
                  'iva'=>0,
                  'iva_r'=>0,
                  'subtotal'=>0,
                  'total'=>0,
                  'correlativo'=>Cotizacione::correlativo($request->tipo_documento),
                  'coniva'=>$request->coniva,
                  'kilometraje'=>$request->kilometraje,
                  'km_proxima'=>$request->km_proxima,
                  'facturar_a'=>$request->facturar_a,
                  'imprimir_veh'=>$request->imprimir_veh,
                ]);

                $vehiculo=Vehiculo::find($request->vehiculo_id);
                $vehiculo->kilometraje=$request->kilometraje;
                $vehiculo->km_proxima=$request->km_proxima;
                $vehiculo->save();


                $trabajo=TrabajoDetalle::create([
                'trabajo_id'=>$trabajo->id,
                'nombre'=>$trabajo->nombre,
                'precio'=>$request->precio,
                'cantidad'=>$request->cantidad,
                'cotizacion_id'=>$coti->id
            ]);
            if($coti->coniva=='si'){
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $nuevoiva=$nuevosubto*\App\Porcentaje::retornar_porcentaje('iva');
                $nuevotot=$nuevoiva+$nuevosubto;
                $coti->subtotal=$nuevosubto;
                $coti->iva=$nuevoiva;
                $coti->total=$nuevotot;
                //$coti->iva=0;
                $coti->save();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $coti->subtotal=$nuevosubto;
                $coti->total=$nuevosubto;
                $coti->save();
                Cotizacione::quitar_ivar($coti->id);
            }

            else:
                $coti=Cotizacione::find($request->cotizacion_id);
                $trabajo=TrabajoDetalle::create([
                    'trabajo_id'=>$trabajo->id,
                    'nombre'=>$trabajo->nombre,
                    'precio'=>$request->precio,
                    'cantidad'=>$request->cantidad,
                    'cotizacion_id'=>$request->cotizacion_id
                ]);
                if($coti->coniva=='si'){
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*1);
                    $nuevoiva=$nuevosubto*\App\Porcentaje::retornar_porcentaje('iva');
                    $nuevotot=$nuevoiva+$nuevosubto;
                    $coti->subtotal=$nuevosubto;
                    $coti->iva=$nuevoiva;
                    $coti->total=$nuevotot;
                    //$coti->iva=0;
                    $coti->save();
                    Cotizacione::carcular_ivar($coti->id);
                }else{
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*1);
                    $coti->subtotal=$nuevosubto;
                    $coti->total=$nuevosubto;
                    $coti->save();
                    Cotizacione::quitar_ivar($coti->id);
                }

            endif;
            DB::commit();
            return array(1,"exito",$coti->id);
          else:
            return array(2,"Primero debe seleccionar un cliente y un vehículo");
          endif;
        }catch(Exception $e){
            DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
    }

    public function guardar(Request $request)
    {
        $this->validar($request->all())->validate();
        try{
            $coti=Cotizacione::find($request->cotizacion_id);
            $trabajo=Trabajo::create([
                'nombre'=>mb_strtoupper($request->nombre),
                'codigo'=>$request->codigo,
                'precio'=>$request->precio
            ]);

            $previa=TrabajoDetalle::create([
                'trabajo_id'=>$trabajo->id,
                'nombre'=>$trabajo->nombre,
                'precio'=>$trabajo->precio,
                'cantidad'=>1,
                'cotizacion_id'=>$request->cotizacion_id
            ]);
            if($coti->coniva=='si'){
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $nuevoiva=$nuevosubto*\App\Porcentaje::retornar_porcentaje('iva');
                $nuevotot=$nuevoiva+$nuevosubto;
                $coti->subtotal=$nuevosubto;
                $coti->iva=$nuevoiva;
                $coti->total=$nuevotot;
                $coti->save();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $coti->subtotal=$nuevosubto;
                $coti->total=$nuevosubto;
                $coti->iva=0;
                $coti->save();
                Cotizacione::quitar_ivar($coti->id);
            }
           
            return array(1,"exito",$trabajo->id,$trabajo->precio);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
    }

    public function guardar2(Request $request)
    {
      $this->validar($request->all())->validate();
      try{
        $repuesto=Trabajo::create([
            'nombre'=>mb_strtoupper($request->nombre),
            'codigo'=>$request->codigo,
            'precio'=>$request->precio
        ]);
        return array(1);
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
          $trabajo=Trabajo::find($id);
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
          $r=trabajo::find($id);
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
    public function destroy(Request $request,$id)
    {
        try{
          $t=Trabajo::find($id);
          if($request->borrar==1):
          $t->estado=2;
          else:
          $t->estado=1;
          endif;
          $t->delete();
          return array(1);
        }catch(Exception $e){
          return array(-1,"error",$e->getMessage());
        }
    }

    protected function validar(array $data)
    {
        $mensajes=array(
          'nombre.required'=>'El nombre del trabajo es obligatorio',
          'nombre.unique'=>'El nombre del trabajo ya existe',
          'nombre.unique'=>'El nombre del trabajo ya existe',
          'precio.required'=>'El precio del trabajo es obligatorio',
      );
      return Validator::make($data, [
          'nombre'=>'required|unique:trabajos',
          'precio'=>'required',
      ],$mensajes);
    }
}
