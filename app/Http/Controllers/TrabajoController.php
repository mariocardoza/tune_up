<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Trabajo;
use App\TrabajoPrevia;
use App\TrabajoDetalle;
use App\Cotizacione;
use Validator;
use DB;

class TrabajoController extends Controller
{
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
                'nombre'=>$request->nombre,
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
                'subtotal'=>0,
                'total'=>0,
                'coniva'=>$request->coniva,
                ]);

                $trabajo=TrabajoDetalle::create([
                'trabajo_id'=>$trabajo->id,
                'precio'=>$request->precio,
                'cantidad'=>$request->cantidad,
                'cotizacion_id'=>$coti->id
            ]);
            if($coti->coniva=='si'){
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*$request->cantidad);
                $nuevoiva=$nuevosubto*session('iva');
                $nuevotot=$nuevoiva+$nuevosubto;
                $coti->subtotal=$nuevosubto;
                $coti->iva=$nuevoiva;
                $coti->total=$nuevotot;
                $coti->save();
            }else{
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*$request->cantidad);
                $coti->subtotal=$nuevosubto;
                $coti->total=$nuevosubto;
                $coti->save();
            }
            if($coti->cliente->sector=='Gran Contribuyente'):
                  $sub=$coti->subtotal;
                  $toti=$coti->total;
                  $nuevoivar=$sub*session('ivar');
                  $nuevotot=$nuevoivar+$toti;
                  $coti->iva_r=$nuevoivar;
                  $coti->total=$nuevotot;
                  $coti->save();
                endif;
            else:
                $coti=Cotizacione::find($request->cotizacion_id);
                $trabajo=TrabajoDetalle::create([
                    'trabajo_id'=>$trabajo->id,
                    'precio'=>$request->precio,
                    'cantidad'=>$request->cantidad,
                    'cotizacion_id'=>$request->cotizacion_id
                ]);
                if($coti->coniva=='si'){
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
                    $nuevoiva=$nuevosubto*session('iva');
                    $nuevotot=$nuevoiva+$nuevosubto;
                    $coti->subtotal=$nuevosubto;
                    $coti->iva=$nuevoiva;
                    $coti->total=$nuevotot;
                    $coti->save();
                }else{
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
                    $coti->subtotal=$nuevosubto;
                    $coti->total=$nuevosubto;
                    $coti->save();
                }
                if($coti->cliente->sector=='Gran Contribuyente'){
                  $sub=$coti->subtotal;
                  $toti=$coti->total;
                  $nuevoivar=$sub*session('ivar');
                  $nuevotot=$nuevoivar+$toti;
                  $coti->iva_r=$nuevoivar;
                  $coti->total=$nuevotot;
                  $coti->save();
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
                'nombre'=>$request->nombre,
                'codigo'=>$request->codigo,
                'precio'=>$request->precio
            ]);

            $previa=TrabajoDetalle::create([
                'trabajo_id'=>$trabajo->id,
                'precio'=>$trabajo->precio,
                'cantidad'=>1,
                'cotizacion_id'=>$request->cotizacion_id
            ]);
            if($coti->coniva=='si'){
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $nuevoiva=$nuevosubto*session('iva');
                $nuevotot=$nuevoiva+$nuevosubto;
                $coti->subtotal=$nuevosubto;
                $coti->iva=$nuevoiva;
                $coti->total=$nuevotot;
                $coti->save();
            }else{
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*1);
                $coti->subtotal=$nuevosubto;
                $coti->total=$nuevosubto;
                $coti->save();
            }
            if($coti->cliente->sector=='Gran Contribuyente'){
                  $sub=$coti->subtotal;
                  $toti=$coti->total;
                  $nuevoivar=$sub*session('ivar');
                  $nuevotot=$nuevoivar+$toti;
                  $coti->iva_r=$nuevoivar;
                  $coti->total=$nuevotot;
                  $coti->save();
                }
            return array(1,"exito",$trabajo->id,$trabajo->precio);
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

    protected function validar(array $data)
    {
        $mensajes=array(
          'nombre.required'=>'El nombre del trabajo es obligatorio',
          'precio.required'=>'El precio del trabajo es obligatorio',
      );
      return Validator::make($data, [
          'nombre'=>'required',
          'precio'=>'required',
      ],$mensajes);
    }
}
