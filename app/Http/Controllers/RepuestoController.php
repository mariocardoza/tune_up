<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repuesto;
use App\RepuestoPrevia;
use App\RepuestoDetalle;
use Validator;
use App\Cotizacione;
use App\Vehiculo;
use DB;

class RepuestoController extends Controller
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
        $repuestos=Repuesto::get();
        return view('repuestos.index',compact('repuestos'));
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
          if($request->vehiculo_id!='' && $request->coniva==''):
            DB::beginTransaction();
            $repuesto=Repuesto::create([
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
                    'subtotal'=>0,
                    'total'=>0,
                    'coniva'=>$request->coniva,
                    'correlativo'=>Cotizacione::correlativo($request->tipo_documento),
                    'kilometraje'=>$request->kilometraje,
                    'km_proxima'=>$request->km_proxima,
                    ]);

                $vehiculo=Vehiculo::find($request->vehiculo_id);
                $vehiculo->kilometraje=$request->kilometraje;
                $vehiculo->km_proxima=$request->km_proxima;
                $vehiculo->save();

                    $rr=RepuestoDetalle::create([
                    'repuesto_id'=>$repuesto->id,
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
                    $coti->iva=0;
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
                $rr=RepuestoDetalle::create([
                    'repuesto_id'=>$repuesto->id,
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
                    $coti->iva=0;
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
            endif;
            DB::commit();
            return array(1,"exito",$coti->id,$repuesto->id,$repuesto->precio,$request->cantidad);
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
        $coti=Cotizacione::find($request->cotizacion_id);
        try{
            DB::beginTransaction();
            $repuesto=Repuesto::create([
                'nombre'=>mb_strtoupper($request->nombre),
                'codigo'=>$request->codigo,
                'precio'=>$request->precio
            ]);

            $previa=RepuestoPrevia::create([
                'repuesto_id'=>$repuesto->id,
                'precio'=>$repuesto->precio,
                'cantidad'=>$request->cantidad
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
                $coti->iva=0;
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
            DB::commit();
            return array(1,"exito",$repuesto->id,$repuesto->precio,$request->cantidad);
        }catch(Exception $e){
            DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
    }

    public function guardar2(Request $request)
    {
      $this->validar2($request->all())->validate();
      try{
        $trabajo=Repuesto::create([
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
        $repuesto=Repuesto::find($id);
        return array(1,"exito",$repuesto);
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
        $this->validar2($request->all())->validate();
        try{
          $r=Repuesto::find($id);
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
    public function destroy(Request $r,$id)
    {
        try{
          $t=Repuesto::find($id);
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
          'nombre.required'=>'El nombre del repuesto es obligatorio',
          'precio.required'=>'El precio del repuesto es obligatorio',
          'cantidad.required'=>'La cantidad es obligatoria',
      );
      return Validator::make($data, [
          'nombre'=>'required',
          'precio'=>'required',
          'cantidad'=>'required'
      ],$mensajes);
    }

     protected function validar2(array $data)
    {
        $mensajes=array(
          'nombre.required'=>'El nombre del repuesto es obligatorio',
          'precio.required'=>'El precio del repuesto es obligatorio',
      );
      return Validator::make($data, [
          'nombre'=>'required',
          'precio'=>'required',
      ],$mensajes);
    }
}
