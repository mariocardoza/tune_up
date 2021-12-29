<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Comprobante</title>
  <!-- Latest compiled and minified CSS -->

  <style>
    html{
      margin:0;
    }
  
    body{
    -webkit-background-size: contain;
    -moz-background-size: contain;
    -o-background-size: contain;
    background-size: contain;
    position: relative;
    }
    .credito{
      position: absolute;
    }
    .detalle{
      font-size: 12px;
    }
  </style>
</head>
<body style="/*background: url({{ public_path('/images/credito.jpg')}}) no-repeat center center fixed;*/">
  <div style="padding: 1.5em;">
    <div style="">
      <div style="height: 6.5em;"></div>
          
      <div style="height: 1.9em;"></div>
      @if($cotizacion->facturar_a=='')
      <div class="credito" style="left:6.56em;margin-top:0.3em;width:25em;">{{$cotizacion->cliente->nombre}}</div>
      <div class="credito" style="left:6.85em;margin-top:3.5em;width:25em;">{{$cotizacion->cliente->direccion}}</div>
      {{-- <div class="credito" style="">{{$cotizacion->cliente->dui}}</div> --}}
      <div class="credito" style="left:36em; margin-top:-1em;">{{$cotizacion->cliente->reg_iva}}</div>
      <div class="credito" style="left:33em;margin-top:0.3em;">{{$cotizacion->cliente->nit}}</div>
      <div class="credito" style="left:33em;margin-top:1.7em;">{{$cotizacion->cliente->giro}}</div>
      @else
      <div class="credito" style="left:6.56em;margin-top:0.3em;width:25em;">{{$cotizacion->facturar_aa->nombre}}</div>
      <div class="credito" style="left:6.85em;margin-top:3.5em;width:22em;">{{$cotizacion->facturar_aa->direccion}}</div>
     {{-- <divclass=""style="">$cotizacion->facturar_aa->dui}}</div> --}}
      <div class="credito" style="left:36em; margin-top:-1em;">{{$cotizacion->facturar_aa->reg_iva}}</div>
      <div class="credito" style="left:33em;margin-top:0.3em;">{{$cotizacion->facturar_aa->nit}}</div>
      <div class="credito" style="left:33em;margin-top:1.7em;">{{$cotizacion->facturar_aa->giro}}</div>
      @endif
      <div class="credito" style="z-Index:7;left:30.1em;
      margin-top:5.2em;width:1.38542in;">  {{$cotizacion->fecha->format('d/m/Y')}}</div>
      @if($cotizacion->imprimir_veh=='si')
        <div class="credito" style="z-Index:7;left:35.1em;
        margin-top:5.2em;width:1.38542in;">{{$cotizacion->vehiculo->marca->marca}}</div>
        <div class="credito" style="z-Index:7;left:39.5em;
        margin-top:5.2em;width:1.38542in;">{{$cotizacion->vehiculo->anio}}</div>
        <div class="credito" style="z-Index:7;left:42.5em;
        margin-top:5.2em;width:1.38542in;">@if($cotizacion->vehiculo->modelo_id!=''){{$cotizacion->vehiculo->modelo->nombre}}@endif</div>
        <div class="credito" style="z-Index:7;left:30em;
        margin-top:7.5em;width:1.38542in;">{{$cotizacion->vehiculo->placa}}</div>
      @endif
      <?php $salto=4.3; $correlativo=1;$total=0; $iva=$cotizacion->iva;?>
      <div class="credito" style="z-Index:3;left:0.91250in;margin-top:11.4em;width:1.92708in;"><b>--- MANO DE OBRA ---</b></div>
      @foreach($cotizacion->trabajodetalle as $t)
      <div class="credito detalle" style="z-Index:4;left:4.6em;top:{{$salto}}in;height:0.15625in;">    {{$t->cantidad}}</div>
      <div class="credito detalle" style="width:34.5em;height:3em;left:6.9em;top:{{$salto}}in;">{{$t->nombre}}</div>

      <div class="credito detalle" style="left:44em;top:{{$salto}}in;">${{number_format($t->precio,2)}}</div>
      <div class="credito detalle" style="z-Index:2;left:56.5em;top:{{$salto}}in;height:0.15625in;text-align:right;">       $ {{number_format($t->precio*$t->cantidad,2)}}</div>
      <?php $salto=$salto+0.2; $correlativo++; $total=$total+($t->cantidad*$t->precio);?>
      @endforeach
      <div class="credito" style="z-Index:3;left:0.91250in;top:{{$salto}}in;width:1.92708in;height:0.15625in;"><b>--- REPUESTOS ---</b></div>

      <?php $salto=$salto+0.2; ?>
      @foreach($cotizacion->repuestodetalle as $r)
      <div class="credito detalle" style="z-Index:4;left:4.6em;top:{{$salto}}in;height:0.15625in;">    {{$r->cantidad}}</div>
      <div class="credito detalle" style="width:34.5em;height:3em;left:6.9em;top:{{$salto}}in;">{{$r->nombre}}</div>

      <div class="credito detalle" style="left:44em;top:{{$salto}}in;">${{number_format($r->precio,2)}}</div>
      <div class="credito detalle" style="z-Index:2;left:56.5em;top:{{$salto}}in;height:0.15625in; text-align:right;">$ {{number_format($r->precio*$r->cantidad,2)}}</div>
      <?php $salto=$salto+0.2; $correlativo++; $total=$total+($r->cantidad*$r->precio);?>
      @endforeach

      <div class="credito detalle" style="z-Index:1;left:56.5em;
top:8.6in;width:1.84375in;height:0.16667in;">{{number_format($cotizacion->subtotal,2)}}
</div>
<div class="credito detalle" style="z-Index:2;left:56.5em;
top:8.9in;width:1.85417in;height:0.16667in;">       {{number_format($cotizacion->iva,2)}}</div>
<div class="credito detalle" style="z-Index:2;left:56.5em;
top:9.2in;width:1.85417in;height:0.16667in;">        {{number_format($cotizacion->subtotal+$cotizacion->iva,2)}}</div>
<div class="credito detalle" style="z-Index:2;left:56.5em;
top:9.5in;width:1.85417in;height:0.16667in;">       {{number_format($cotizacion->iva_r,2)}}</div>
<div class="credito detalle" style="z-Index:2;left:56.5em;
top:10.25in;width:1.85417in;height:0.16667in;">         {{number_format($cotizacion->total,2)}}</div>
<div class="credito " style="z-Index:4;left:4em;
top:8.72083in;width:4.28125in;height:0.44792in;">{{numaletras($cotizacion->total)}} </div>
<div class="credito detalle" style="z-Index:4;left:1in;
top:8.2in;width:4.28125in;height:0.44792in;">KM RECEPCIÓN {{number_format($cotizacion->kilometraje,0)}} </div>
<div class="credito detalle" style="z-Index:4;left:2.72292in;
top:8.2in;width:4.28125in;height:0.44792in;">KM PRÓXIMA {{number_format($cotizacion->km_proxima,0)}} </div>
<div class="FRX1_37" style="z-Index:6;left:5.87708in;
top:9.68333in;width:1.20000in;height:0.19792in;"></div>
      <br>
      
    </div>
  </div>
</body>
</html>