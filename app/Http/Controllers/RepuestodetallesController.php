<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RepuestoPrevia;
use App\RepuestoDetalle;
use App\Vehiculo;
use App\Repuesto;
use Validator;
use DB;
use App\Cotizacione;

class RepuestodetallesController extends Controller
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

                $rrr=Repuesto::find($request->repuesto_id);

                $trabajo=RepuestoDetalle::create([
                'repuesto_id'=>$request->repuesto_id,
                'nombre'=>$rrr->nombre,
                'precio'=>$request->precio,
                'cantidad'=>$request->cantidad,
                'cotizacion_id'=>$coti->id
            ]);
                if($coti->coniva=='si'){
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
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
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
                    $coti->subtotal=$nuevosubto;
                    $coti->total=$nuevosubto;
                    $coti->iva=0;
                    $coti->save();
                    Cotizacione::quitar_ivar($coti->id);
                }

            else:
                $coti=Cotizacione::find($request->cotizacion_id);
                $rrr=Repuesto::find($request->repuesto_id);
                $trabajo=RepuestoDetalle::create([
                    'repuesto_id'=>$request->repuesto_id,
                    'nombre'=>$rrr->nombre,
                    'precio'=>$request->precio,
                    'cantidad'=>$request->cantidad,
                    'cotizacion_id'=>$request->cotizacion_id
                ]);
                if($coti->coniva=='si'){
                    $sub=$coti->subtotal;
                    $toti=$coti->total;
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
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
                    $nuevosubto=$sub+($request->precio*$request->cantidad);
                    $coti->subtotal=$nuevosubto;
                    $coti->total=$nuevosubto;
                    $coti->iva=0;
                    $coti->save();
                    Cotizacione::quitar_ivar($coti->id);
                }
               
            endif;
            /*$trabajo=TrabajoPrevia::create([
                'trabajo_id'=>$request->trabajo_id,
                'precio'=>$request->precio,
                'cantidad'=>$request->cantidad
            ]);*/
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
            DB::beginTransaction();
            $coti=Cotizacione::find($request->cotizacion_id);
            $repuesto=Repuesto::find($request->repuesto_id);
            $trabajo=RepuestoDetalle::create([
                'repuesto_id'=>$request->repuesto_id,
                'nombre'=>$repuesto->nombre,
                'precio'=>$request->precio,
                'cantidad'=>$request->cantidad,
                'cotizacion_id'=>$request->cotizacion_id
            ]);
            if($coti->coniva=='si'){
                $sub=$coti->subtotal;
                $toti=$coti->total;
                $nuevosubto=$sub+($request->precio*$request->cantidad);
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
                $nuevosubto=$sub+($request->precio*$request->cantidad);
                $coti->subtotal=$nuevosubto;
                $coti->total=$nuevosubto;
                $coti->iva=0;
                $coti->save();
                Cotizacione::quitar_ivar($coti->id);
            }
            
            DB::commit();
            return array(1,"exito");
        }catch(Exception $e){
            DB::rollBack();
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
        $retorno=RepuestoPrevia::modal_edit($id);
        return $retorno;
    }

    public function edit2($id)
    {
        $retorno=RepuestoDetalle::modal_edit($id);
        return $retorno;
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
            DB::beginTransaction();
            $coti=Cotizacione::find($request->cotizacion_id);
            $repuesto=RepuestoDetalle::find($id);
            //quitar ala cotizacion lo que tiene en el repuesto
            $totalante=$repuesto->cantidad*$repuesto->precio;
            $cotitot=$coti->subtotal;
            $coti->subtotal=$cotitot-$totalante;
            $coti->save();
            //edito el repuesto
            $repuesto->precio=$request->precio;
            $repuesto->cantidad=$request->cantidad;
            $repuesto->nombre=$request->nombre;
            $repuesto->cotizacion_id=$request->cotizacion_id;
            $repuesto->save();
            if($coti->coniva=='si'){
                $tot=$request->precio*$request->cantidad;
                $subto=$coti->subtotal;
                $n=$subto+$tot;
                $iva=$n*\App\Porcentaje::retornar_porcentaje('iva');
                $nt=$n+$iva;
                $coti->subtotal=$n;
                $coti->iva=$iva;
                $coti->total=$nt;
                $coti->save();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $tot=$request->precio*$request->cantidad;
                $subto=$coti->subtotal;
                $n=$subto+$tot;
                $coti->subtotal=$n;
                $coti->total=$n;
                $coti->iva=0;
                $coti->save();
                Cotizacione::quitar_ivar($coti->id);
            }

            DB::commit();
            return array(1,"exito");
        }catch(Exception $e){
          DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
    }

    public function update2(Request $request, $id)
    {
        try{
            DB::beginTransaction();
            $coti=Cotizacione::find($request->cotizacion_id);
            $repuesto=RepuestoDetalle::find($id);
            //quitar ala cotizacion lo que tiene en el repuesto
            $totalante=$repuesto->cantidad*$repuesto->precio;
            $cotitot=$coti->subtotal;
            $coti->subtotal=$cotitot-$totalante;
            $coti->save();
            //edito el repuesto
            $repuesto->precio=$request->precio;
            $repuesto->cantidad=$request->cantidad;
            $repuesto->nombre=$request->nombre;
            $repuesto->cotizacion_id=$request->cotizacion_id;
            $repuesto->save();
            if($coti->coniva=='si'){
                $tot=$request->precio*$request->cantidad;
                $subto=$coti->subtotal;
                $n=$subto+$tot;
                $iva=$n*\App\Porcentaje::retornar_porcentaje('iva');
                $nt=$n+$iva;
                $coti->subtotal=$n;
                $coti->iva=$iva;
                $coti->total=$nt;
                $coti->save();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $tot=$request->precio*$request->cantidad;
                $subto=$coti->subtotal;
                $n=$subto+$tot;
                $coti->subtotal=$n;
                $coti->total=$n;
                $coti->iva=0;
                $coti->save();
                Cotizacione::quitar_ivar($coti->id);
            }
            DB::commit();
            return array(1,"exito");
        }catch(Exception $e){
            DB::rollBack();
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
          DB::beginTransaction();
            $coti=Cotizacione::find($request->cotizacion_id);
            $deta=RepuestoDetalle::find($id);
            
            if($coti->coniva=='si'){
                $tot=$deta->precio*$deta->cantidad;
                $subto=$coti->subtotal;
                $n=$subto-$tot;
                $iva=$n*\App\Porcentaje::retornar_porcentaje('iva');
                $nt=$n+$iva;
                $coti->subtotal=$n;
                $coti->iva=$iva;
                $coti->total=$nt;
                $coti->save();
                $deta->delete();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $tot=$deta->precio*$deta->cantidad;
                $subto=$coti->subtotal;
                $n=$subto-$tot;
                $coti->subtotal=$n;
                $coti->total=$n;
                $coti->iva=0;
                $coti->save();
                $deta->delete();
                Cotizacione::quitar_ivar($coti->id);
            }

            DB::commit();
            if((count($coti->repuestodetalle) == 0) && (count($coti->trabajodetalle)==0)){
                $coti->delete();
                return array(1,"exito",0);
            }else{
                return array(1,"exito",$coti->id);
            }
        }catch(Exception $e){
          DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
    }

    public function destroy2(Request $request,$id)
    {
        try{
            $coti=Cotizacione::find($request->cotizacion_id);
            $deta=RepuestoDetalle::find($id);
            
            if($coti->coniva=='si'){
                $tot=$deta->precio*$deta->cantidad;
                $subto=$coti->subtotal;
                $n=$subto-$tot;
                $iva=$n*\App\Porcentaje::retornar_porcentaje('iva');
                $nt=$n+$iva;
                $coti->subtotal=$n;
                $coti->iva=$iva;
                $coti->total=$nt;
                $coti->save();
                $deta->delete();
                Cotizacione::carcular_ivar($coti->id);
            }else{
                $tot=$deta->precio*$deta->cantidad;
                $subto=$coti->subtotal;
                $n=$subto-$tot;
                $coti->subtotal=$n;
                $coti->total=$n;
                $coti->iva=0;
                $coti->save();
                $deta->delete();
                Cotizacione::quitar_ivar($coti->id);
            }

            return array(1,"exito",$coti->id);
        }catch(Exception $e){
            return array(-1,"error",$e->getMessage());
        }
    }

     protected function validar(array $data)
    {
        $mensajes=array(
          'repuesto_id.required'=>'El nombre del repuesto es obligatorio',
          'precio.required'=>'El precio del repuesto es obligatorio',
          'cantidad.required'=>'La cantidad es obligatoria',
      );
      return Validator::make($data, [
          'repuesto_id'=>'required',
          'precio'=>'required',
          'cantidad'=>'required',
      ],$mensajes);
    }
}
