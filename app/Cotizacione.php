<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cotizacione extends Model
{
    protected $guarded = [];
    protected $dates = ['fecha','created_at'];

    public function cliente()
    {
    	return $this->belongsTo('App\Cliente')->withDefault();
    }

    public function facturar_aa()
    {
        return $this->belongsTo('App\Cliente','facturar_a')->withDefault();
    }

    public function vehiculo()
    {
    	return $this->belongsTo('App\Vehiculo')->withDefault();
    }

    public function repuestodetalle()
    {
    	return $this->hasMany('App\RepuestoDetalle','cotizacion_id');
    }

    public function trabajodetalle()
    {
    	return $this->hasMany('App\TrabajoDetalle','cotizacion_id');
    }

    public static function correlativo($tipo_documento)
    {
        if($tipo_documento==1){
            $numero=Cotizacione::where('tipo_documento',$tipo_documento)->count();
            return $numero+1;  
        }
        if($tipo_documento==2){
            $numero=72;
            $numero2=Cotizacione::where('tipo_documento',$tipo_documento)->count();
            return $numero+$numero2;  
        }
        if($tipo_documento==3){
            $numero=377;
            $numero2=Cotizacione::where('tipo_documento',$tipo_documento)->count();
            return $numero+$numero2;
        }else{
            $numero2=Cotizacione::where('tipo_documento',$tipo_documento)->count();
            return $numero2+1;  
        }
        
    }


    public static function obtenerprevias($id)
    {
        $coti=Cotizacione::find($id);
    	$repuestos=RepuestoDetalle::where('cotizacion_id',$id)->get();
    	$trabajos=TrabajoDetalle::where('cotizacion_id',$id)->get();
        $total=0.0;
    	$html='';
        $tfoot="";
    

    	foreach ($trabajos as $i=> $t) {
    		$html.='<tr>
				
				<td>'.$t->nombre.'</td>
				<td>'.number_format($t->precio,2).'</td>
				<td>'.$t->cantidad.'</td>
				<td>'.number_format($t->precio*$t->cantidad,2).'</td>
				<td>
					<button title="Editar trabajo" type="button" class="btn btn-warning btn-sm" id="editar_trabajo" data-id="'.$t->id.'"><i class="fas fa-edit"></i></button>
					<button title="Eliminar trabajo" type="button" class="btn btn-danger btn-sm" id="eliminar_trabajo" data-id="'.$t->id.'"><i class="fas fa-trash"></i></button>
				</td>
    		</tr>';
            $total=$total+($t->cantidad*$t->precio);
    	}

            foreach ($repuestos as $i=> $r) {
            $html.='<tr>
                
                <td>'.$r->nombre.'</td>
                <td>'.number_format($r->precio,2).'</td>
                <td>'.$r->cantidad.'</td>
                <td>'.number_format($r->precio*$r->cantidad,2).'</td>
                <td>
                    <button title="Editar repuesto" type="button" id="editar_repuesto" data-id="'.$r->id.'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                    <button title="Eliminar repuesto" type="button" id="eliminar_repuesto" data-id="'.$r->id.'" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </td>
            </tr>';
            $total=$total+($r->cantidad*$r->precio);
        }
        $tfoot.='
            <tr>
                <th  colspan="3">NETO</th>
                <th class="trr">$'.number_format($coti->subtotal,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="3">IVA</th>
                <th class="thiva">$'.number_format($coti->iva,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th  colspan="3">SUBTOTAL</th>
                <th class="thst">$'.number_format($coti->subtotal+$coti->iva,2).'</th>
                <th></th>
            </tr>
             <tr>
                <th  colspan="3">1% RET</th>
                <th class="thivar">$'.number_format($coti->iva_r,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="3">TOTAL</th>
                <th class="thtotal">$'.number_format($coti->total,2).'</th>
                <th></th>
            </tr>
            ';

    	return array(1,"exito",$html,$tfoot,$coti->total);
    }

    public static function obtenerguardadas($id)
    {
    	$coti=Cotizacione::find($id);
    	$repuestos=RepuestoDetalle::where('cotizacion_id',$coti->id)->get();
    	$trabajos=TrabajoDetalle::where('cotizacion_id',$coti->id)->get();
    	$html='';
        $tfoot="";
    	
    	foreach ($trabajos as $i=> $t) {
    		$html.='<tr style="font-size: 13px;">
				<td>'.$t->nombre.'</td>
				<td>'.number_format($t->precio,2).'</td>
				<td>'.$t->cantidad.'</td>
				<td>'.number_format($t->precio*$t->cantidad,2).'</td>';
                if($coti->estado==1):
					$html.='<td><button title="Editar trabajo" type="button" class="btn btn-warning btn-sm" id="editar_trabajo" data-id="'.$t->id.'"><i class="fas fa-edit"></i></button>
					<button title="Eliminar trabajo" type="button" class="btn btn-danger btn-sm" id="eliminar_trabajo" data-id="'.$t->id.'"><i class="fas fa-trash"></i></button></td>';
                endif;
			$html.='</tr>';
    	}

        foreach ($repuestos as $i=> $r) {
            $html.='<tr style="font-size: 13px;">
                <td>'.$r->nombre.'</td>
                <td>'.number_format($r->precio,2).'</td>
                <td>'.$r->cantidad.'</td>
                <td>'.number_format($r->precio*$r->cantidad,2).'</td>';
                if($coti->estado==1):
                    $html.='<td><button title="Editar repuesto" type="button" id="editar_repuesto" data-id="'.$r->id.'" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                    <button title="Eliminar repuesto" type="button" id="eliminar_repuesto" data-id="'.$r->id.'" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>

                </td>';
            endif;
            $html.='</tr>';
        }

        $tfoot.='
            <tr>
                <th class="trr" colspan="3">NETO</th>
                <th>$'.number_format($coti->subtotal,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="3">IVA</th>
                <th class="thiva">$'.number_format($coti->iva,2).'</th>
                <th></th>
            </tr>
  
            <tr>
                <th colspan="3">SUBTOTAL</th>
                <th>$'.number_format($coti->subtotal+$coti->iva,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="3">1% RET</th>
                <th>$'.number_format($coti->iva_r,2).'</th>
                <th></th>
            </tr>
            <tr>
                <th colspan="3">TOTAL</th>
                <th class="thtotal">$'.number_format($coti->total,2).'</th>
                <th></th>
            </tr>
            ';

    	return array(1,"exito",$html,$tfoot);
    }

    public static function carcular_ivar($id)
    {
        $coti=Cotizacione::find($id);
        if($coti->cliente->sector=='Gran Contribuyente'):
            $sub=$coti->subtotal;
            $toti=$coti->total;
            if($sub>=100):
                $nuevoivar=$sub*session('ivar');
                $nuevotot=$toti-$nuevoivar;
                $coti->iva_r=$nuevoivar;
                $coti->total=$nuevotot;
                $coti->save();
            else:
                $elr=$coti->iva_r;
                $coti->total=$coti->total+$elr;
                $coti->iva_r=0;
                $coti->save();
            endif;
        endif;
    }

    public static function aplicar_iva($id)
    {
        $coti=Cotizacione::find($id);
        $subto=$coti->subtotal;
        $iva=session('iva')*$subto;
        $total=$coti->total;
        $nt=$total+$iva;
        $coti->iva=$iva;
        $coti->total=$nt;
        $coti->coniva='si';
        $coti->save();
    }

    public static function quitar_iva($id)
    {
        $coti=Cotizacione::find($id);
        $total=$coti->total;
        $iva=$coti->iva;
        $nt=$total-$iva;
        $coti->iva=0.0;
        $coti->total=$nt;
        $coti->coniva='no';
        $coti->save();
        $elr=$coti->iva_r;
        $coti->total=$coti->total+$elr;
        $coti->iva_r=0;
        $coti->save();
    }

    public static function quitar_ivar($id)
    {
        $coti=Cotizacione::find($id);
        $elr=$coti->iva_r;
        $coti->total=$coti->total+$elr;
        $coti->iva_r=0;
        $coti->save();
    }

    public static function convertir($id,$tipo,$fecha)
    {
        $coti=Cotizacione::find($id);
        try{
            DB::beginTransaction();
            $nueva=Cotizacione::create([
                'vehiculo_id'=>$coti->vehiculo_id,
                'cliente_id'=>$coti->cliente_id,
                'tipo_documento'=>$tipo,
                'n_impresiones'=>0,
                'fecha'=>invertir_fecha($fecha),
                'iva'=>$coti->iva,
                'iva_r'=>$coti->iva_r,
                'subtotal'=>$coti->subtotal,
                'total'=>$coti->total,
                'coniva'=>$coti->coniva,
                'correlativo'=>Cotizacione::correlativo($tipo),
                'correlativo_cotizacion'=>$coti->correlativo,
                'kilometraje'=>$coti->kilometraje,
                'km_proxima'=>$coti->km_proxima,
            ]);
            foreach($coti->trabajodetalle as $t):
                $t_detalle=TrabajoDetalle::create([
                    'trabajo_id'=>$t->trabajo_id,
                    'precio'=>$t->precio,
                    'cantidad'=>$t->cantidad,
                    'nombre'=>$t->nombre,
                    'cotizacion_id'=>$nueva->id
                ]);
            endforeach;
            foreach($coti->repuestodetalle as $r):
                $r_detalle=RepuestoDetalle::create([
                    'repuesto_id'=>$r->repuesto_id,
                    'precio'=>$r->precio,
                    'cantidad'=>$r->cantidad,
                    'nombre'=>$r->nombre,
                    'cotizacion_id'=>$nueva->id
                ]);
            endforeach;
            $coti->estado=2;
            $coti->save();
            DB::commit();
            return array(3,$nueva,$nueva->id);
        }catch(Exception $e){
            DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
        //return array(3,$cotizacion);
    }

    public static function clonar($id,$tipo,$fecha)
    {
        $coti=Cotizacione::find($id);
        try{
            DB::beginTransaction();
            $nueva=Cotizacione::create([
                'vehiculo_id'=>$coti->vehiculo_id,
                'cliente_id'=>$coti->cliente_id,
                'tipo_documento'=>$tipo,
                'n_impresiones'=>0,
                'fecha'=>invertir_fecha($fecha),
                'iva'=>$coti->iva,
                'iva_r'=>$coti->iva_r,
                'subtotal'=>$coti->subtotal,
                'total'=>$coti->total,
                'coniva'=>$coti->coniva,
                'facturar_a'=>$coti->facturar_a,
                'imprimir_veh'=>$coti->imprimir_veh,
                'correlativo'=>Cotizacione::correlativo($tipo),
                //'correlativo_cotizacion'=>$coti->correlativo,
                'kilometraje'=>$coti->kilometraje,
                'km_proxima'=>$coti->km_proxima,
            ]);
            foreach($coti->trabajodetalle as $t):
                $t_detalle=TrabajoDetalle::create([
                    'trabajo_id'=>$t->trabajo_id,
                    'precio'=>$t->precio,
                    'cantidad'=>$t->cantidad,
                    'nombre'=>$t->nombre,
                    'cotizacion_id'=>$nueva->id
                ]);
            endforeach;
            foreach($coti->repuestodetalle as $r):
                $r_detalle=RepuestoDetalle::create([
                    'repuesto_id'=>$r->repuesto_id,
                    'precio'=>$r->precio,
                    'cantidad'=>$r->cantidad,
                    'nombre'=>$r->nombre,
                    'cotizacion_id'=>$nueva->id
                ]);
            endforeach;
            $coti->estado=2;
            $coti->save();
            DB::commit();
            return array(3,$nueva,$nueva->id);
        }catch(Exception $e){
            DB::rollback();
            return array(-1,"error",$e->getMessage());
        }
        //return array(3,$cotizacion);
    }

    public static function calcular_subtotal($id)
    {
        $coti=Cotizacione::find($id);
        $repuestos=RepuestoDetalle::where('cotizacion_id',$id)->get();
        $trabajos=TrabajoDetalle::where('cotizacion_id',$id)->get();
        $total=0;

        foreach ($trabajos as $i=> $t) {
            $total=$total+($t->cantidad*$t->precio);
        }

        foreach ($repuestos as $i=> $r) {
            $total=$total+($r->cantidad*$r->precio);
        }

        $coti->subtotal=$total;
        $coti->total=$total;
        $coti->save();
    }
}
