<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cotizacione;
use App\Cliente;
use App\Vehiculo;
use App\RepuestoPrevia;
use App\TrabajoPrevia;
use App\RepuestoDetalle;
use App\TrabajoDetalle;
use App\Taller;
use Validator;
use DB;
use PDF;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;

class CotizacionController extends Controller
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
    public function obtenerprevia($id)
    {
        $retorno=Cotizacione::obtenerprevias($id);
        return $retorno;
    }

    public function obtenerguardadas($id)
    {
        $retorno=Cotizacione::obtenerguardadas($id);
        return $retorno;
    }

    public function obtenervehiculos($id,Request $request)
    {
        $retorno=Vehiculo::obtenervehiculos($id,$request->actual);
        return $retorno;
    }

    public function convertir(Request $request)
    {
      try{
        $ruta="";
        $coti=Cotizacione::find($request->id);
        $correlativo_a=$coti->correlativo;
        $coti->correlativo_cotizacion=$correlativo_a;
        $coti->tipo_documento=$request->estado;
        $coti->fecha=invertir_fecha($request->fecha);
        $coti->correlativo=Cotizacione::correlativo($request->estado);
        $coti->save();
        if($request->estado==2){
          $ruta="../facturas/".$request->id;
        }else if($request->estado==3){
          $ruta="../creditos/".$request->id;
        }else{
          $ruta="../exportaciones/".$request->id;
        }
        return array(1,$coti,$ruta);
      }catch(Exception $e){
        return array(-1,"error",$e->getMessage());
      }
    }

    public function index()
    {
        $cotizaciones=Cotizacione::where('tipo_documento',1)->get();
        return view('cotizaciones.index',compact('cotizaciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $correlativo=Cotizacione::correlativo(1);
        $clientes=Cliente::where('estado',1)->get();
        return view('cotizaciones.create',compact('clientes','correlativo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $total=0.0;
            DB::beginTransaction();
                       
            $cotizacion=Cotizacione::create([
            'vehiculo_id'=>$request->vehiculo_id,
            'cliente_id'=>$request->cliente_id,
            'tipo_documento'=>1,
            'n_impresiones'=>1,
            'fecha'=>invertir_fecha($request->fecha),
            'iva'=>$request->iva,
            'subtotal'=>$request->subtotal,
            'total'=>$request->total,
            'coniva'=>$request->eliva,
            ]);
            
             $trabajos_p=TrabajoPrevia::all();
            foreach ($trabajos_p as $t) {
                $t_detalle=TrabajoDetalle::create([
                    'trabajo_id'=>$t->trabajo_id,
                    'precio'=>$t->precio,
                    'cantidad'=>$t->cantidad,
                    'cotizacion_id'=>$cotizacion->id
                ]);
                $total=$total+($t->precio*$t->cantidad);
            }
            $repuestos_p=RepuestoPrevia::all();
            foreach ($repuestos_p as $r) {
                $r_detalle=RepuestoDetalle::create([
                    'repuesto_id'=>$r->repuesto_id,
                    'precio'=>$r->precio,
                    'cantidad'=>$r->cantidad,
                    'cotizacion_id'=>$cotizacion->id
                ]);
                $total=$total+($r->precio*$r->cantidad);
            }
           
            $vehiculo=Vehiculo::find($request->vehiculo_id);
            $vehiculo->kilometraje=$request->kilometraje;
            $vehiculo->km_proxima=$request->km_proxima;
            $vehiculo->save();

            if($request->eliva='si'){
                $eliva=$total*session('iva');
                $cotizacion->subtotal=$total;
                $cotizacion->iva=$eliva;
                $cotizacion->total=($eliva+$total);
                $cotizacion->save();
            }

            DB::commit();
            RepuestoPrevia::truncate();
            TrabajoPrevia::truncate();
            return array(1,"exito",$cotizacion->id);
        }catch(Exception $e){
            DB::rollBack();
            return array(-1,"error",$e->getMessage(),$total);
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
        if($id!=0)
        {
          $siguiente=$anterior=$primera=$ultima=0;
            $cotizacion=Cotizacione::findorFail($id);
            $p=Cotizacione::where('tipo_documento',1)->orderby('fecha','asc')->first();
            $u=Cotizacione::where('tipo_documento',1)->orderBy('fecha','desc')->first();
            $s= Cotizacione::where('id','>', intval($id))->where('tipo_documento',1)->orderBy('fecha', 'asc')->first();
            $a= Cotizacione::where('id','<', intval($id))->where('tipo_documento',1)->orderBy('fecha', 'desc')->first();
            $clientes=Cliente::where('estado',1)->get();
            
            if($s != null){
                $siguiente=$s->id;
            }
            if($a != null){
                $anterior=$a->id;
            }
            if($p !=null){
              $primera=$p->id;
            }
              if($u !=null){
              $ultima=$u->id;
            }

            
            return view('cotizaciones.show',compact('cotizacion','clientes','siguiente','anterior','primera','ultima'));  
        }else{
            $clientes=Cliente::where('estado',1)->get();
            return redirect('cotizaciones/create')->with('clientes');
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
        try{
          DB::beginTransaction();
          $coti=Cotizacione::find($id);
          $rd=RepuestoDetalle::where('cotizacion_id',$id)->delete();
          $td=TrabajoDetalle::where('cotizacion_id',$id)->delete();
          $coti->delete();
          DB::commit();
          return array(1);
        }catch(Exception $e){
          DB::rollBack();
          return array(-1,"error",$e->getMessage());
        }
    }

    public function ivaventas(Request $r)
    {
        $fecha1=invertir_fecha($r->fecha1);
        $fecha2=invertir_fecha($r->fecha2);
        $cotizaciones=Cotizacione::where('fecha','>=',$fecha1)->where('fecha','<=',$fecha2)->get();
        $f1=$r->fecha1;
        $f2=$r->fecha2;
        //dd($cotizaciones);
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('cotizaciones.ivaventas',compact('cotizaciones','f1','f2'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('iva.pdf');
    }

    public function pdf($id)
    {
        $cotizacion=Cotizacione::find($id);
        $taller=Taller::find(1);
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('cotizaciones.prueba',compact('cotizacion','taller'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream('cotizacion.pdf');
    }

    public function email($id)
    {
      $cotizacion= \App\Cotizacione::find($id);
        try{
          $cotizacion=Cotizacione::find($id);
        $taller=Taller::find(1);
        //dd($cotizacion->repuestodetalle);
        $pdf = \PDF::loadView('cotizaciones.prueba',compact('cotizacion','taller'));
        $pdf->setPaper('letter', 'portrait');
        $nom=date("d_m_Y_H:i:s").'_cotizacion.pdf';
        return $pdf->download($nom);


         
        }catch(Exception $e){

        }
    }

    public function enviar(Request $request)
    {
      $this->validar($request->all())->validate();
      $cotizacion=Cotizacione::find($request->id);
      $clienti=$cotizacion->cliente;
      $clienti->correo=$request->correo;
      $clienti->save();
      if($request->adicional!='' && filter_var($request->adicional, FILTER_VALIDATE_EMAIL)):
        $retorno=Mail::send('cotizaciones.email', compact('cotizacion'),function (Message $message) use ($request,$cotizacion){
        $message->to($request->correo,$cotizacion->cliente->nombre)
        ->from('tuneupservis@gmail.com','TUNE - UP SERVICE')
        ->cc($request->adicional,'')
        ->replyTo('h_rivas47@yahoo.com', 'Héctor Rivas')
        ->subject('Cotización N°: '.$cotizacion->correlativo);
      });
      else:
        $retorno=Mail::send('cotizaciones.email', compact('cotizacion'),function (Message $message) use ($request,$cotizacion){
        $message->to($request->correo,$cotizacion->cliente->nombre)
        ->from('tuneupservis@gmail.com','TUNE - UP SERVICE')
        ->subject('Cotización N°: '.$cotizacion->correlativo);
      });
      endif;
    
      $response = [
        'status' => 'success',
        'msg' => 'Mail sent successfully',
    ];
      return response()->json([$response], 200);
      
    }

    public function el_via(Request $r,$id)
    {
      if($r->aplicariva=='si')
      {
        $coti=Cotizacione::find($id);
        $subto=$coti->subtotal;
        $ivar=$coti->iva_r;
        $iva=session('iva')*$subto;
        $total=$coti->total;
        $nt=$total+$iva+$ivar;
        $coti->iva=$iva;
        $coti->total=$nt;
        $coti->coniva='si';
        $coti->save();
        return array(1,'exito');
      }else{
        $coti=Cotizacione::find($id);
        $total=$coti->total;
        $iva=$coti->iva;
        $nt=$total-$iva;
        $coti->iva=0.0;
        $coti->total=$nt;
        $coti->coniva='no';
        $coti->save();
        return array(1,'exito');
      }
    }

     protected function validar(array $data)
    {
        $mensajes=array(
          'correo.required'=>'Debe ingresar el correo electrónico',
          'correo.email'=>'Debe ser un correo electrónico válido',
          'adicional.email'=>'El correo adicional debe ser un correo electrónico válido',
      );
      return Validator::make($data, [
          'correo'=>'required|email',
          //'adicional'=>'email',
      ],$mensajes);
    }
}
