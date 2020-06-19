<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Historial vehículo: {{$carro->placa}}</title>
</head>
<style>
	.encabezado{
border-radius: 15px;
border : 0.1px solid #000000;
font-family : Arial, Verdana, Helvetica, sans-serif;
font-size : 15px;
padding-left : 5px;
padding-right : 5px;
}

.titulo{
	font-family : Arial, Verdana, Helvetica, sans-serif;
}

.tablita{
border-radius: 15px;
border : 0.1px solid #000000;
font-family : Arial, Verdana, Helvetica, sans-serif;
font-size : 11px;
padding-left : 5px;
padding-right : 5px;
}

.tablita2{
font-family : Arial, Verdana, Helvetica, sans-serif;
font-size : 11px;
padding-left : 5px;
padding-right : 5px;
}
.page-break {
    page-break-after: always;
}
.fecha{
	font-family : Arial, Verdana, Helvetica, sans-serif;
	font-size : 11px;
	font-weight: bold;
}
</style>
<body>
<script type="text/php">
    if ( isset($pdf) ) {
        $pdf->page_script('
            $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
            $pdf->text(502, 10, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
        ');
    }

</script>
	<div style="text-align: right;" class="fecha">FECHA: {{date("d/m/Y")}}</div>
	<div style="text-align:center; font-size: 20px; font-weight: bold;" class="titulo">TUNE-UP</div>
	<div style="text-align:center;" class="titulo">SERVICE</div>
	<div style="text-align:center;" class="titulo">REPORTE DE TRABAJOS REALIZADOS A VEHÍCULOS</div><br>
	<table class="encabezado" width="100%">
		<tr>
			<td colspan="4">
				<span style="text-align:left;"><b>NOMBRE DEL CLIENTE:</b></span><br>
				<span style="text-align:center;">{{$carro->cliente->nombre}}</span>
				<hr>
			</td>
		</tr>

		<tr>
			<td>
				<b style="z-Index:1; left: 1in;">PLACA N°</b>
			</td>
			<td><b>MARCA</b></td>
			<td><b>MODELO</b></td>
			<td><b>AÑO</b></td>
		</tr>
		<tr>
			<td>
				<span style="z-Index:1; left: 1in;">{{$carro->placa}}</span>
			</td>
			<td><span>@if($carro->marca_id!=''){{$carro->marca->marca}}@endif</span></td>
			<td><span>@if($carro->modelo_id!=''){{$carro->modelo->nombre}}@endif</span></td>
			<td><span>{{$carro->anio}}</span></td>
		</tr>
	</table>
	<br><br>
	<?php $eltotal=0.0; ?>
	@foreach($carro->cotis_activas as $c)
	<?php $eltotal=$eltotal+$c->subtotal; ?>
	<table class="tablita">
		<tr>
			<td>COMPROBANTE N°: {{$c->correlativo}}</td><td>FECHA: {{$c->fecha->format("d/m/Y")}}</td>
		</tr>
	</table>
	<table class="tablita" width="100%" style="background: #F0E9EA">
		<tr>
			<td width="5%" rowspan="2">COR</td>
			<td width="16%" colspan="2" style="text-align: center;">KILOMETRAJE</td>
			<td width="54%" rowspan="2">DESCRIPCIÓN TRABAJO/REPUESTO</td>
			<td width="5%" rowspan="2" style="text-align: left;">CANTIDAD</td>
			<td width="10%" rowspan="2">P. UNITARIO ($)</td>
			<td width="10%" rowspan="2">TOTAL ($)</td>
		</tr>
		<tr>
			<td>RECEPCIÓN</td>
			<td>PROX. REVISIÓN</td>
		</tr>
	</table>
	<table class="tablita2" width="100%" rules="">
		<tr>
			<td width="5%" rowspan="1">&nbsp;</td>
			<td width="8%" colspan="1">{{$c->kilometraje}}</td>
			<td width="8%" colspan="1">{{$c->km_proxima}}</td>
			<td width="54%" rowspan="1">&nbsp;</td>
			<td width="5%" rowspan="1">&nbsp;</td>
			<td width="10%" rowspan="1">&nbsp;</td>
			<td width="10%" rowspan="1">&nbsp;</td>
		</tr>
	</table>
	<?php $correlativo=0; ?>
		@foreach($c->trabajodetalle as $i=> $t)
		<?php $correlativo++; ?>
			<table class="tablita2" width="100%" rules="">
				<tr>
					<td width="5%" rowspan="1">{{$correlativo}}</td>
					<td width="8%" colspan="1">&nbsp;</td>
					<td width="8%" colspan="1">&nbsp;</td>
					<td width="54%" style="text-align: left;" rowspan="1">{{$t->nombre}}</td>
					<td width="5%" style="text-align: center;" rowspan="1">{{$t->cantidad}}</td>
					<td width="10%" style="text-align: right;" rowspan="1">{{number_format($t->precio,2)}}</td>
					<td width="10%" style="text-align: right;" rowspan="1">{{number_format($t->precio*$t->cantidad,2)}}</td>
				</tr>
			</table>
		@endforeach
		@foreach($c->repuestodetalle as $r)
		<?php $correlativo++; ?>
			<table class="tablita2" width="100%" rules="">
				<tr>
					<td width="5%" rowspan="1">{{$correlativo}}</td>
					<td width="8%" colspan="1">&nbsp;</td>
					<td width="8%" colspan="1">&nbsp;</td>
					<td width="54%" style="text-align: left;" rowspan="1">{{$r->nombre}}</td>
					<td width="5%" style="text-align: center;" rowspan="1">{{$r->cantidad}}</td>
					<td width="10%" style="text-align: right;" rowspan="1">{{number_format($r->precio,2)}}</td>
					<td width="10%" style="text-align: right;" rowspan="1">{{number_format($r->precio*$r->cantidad,2)}}</td>
				</tr>
			</table>
		@endforeach
		<table class="tablita" width="100%" rules="">
			<tr>
				<td  style="text-align: right;" >TOTAL: {{number_format($c->subtotal,2)}}</td>
			</tr>
		</table>
	<br>
	@endforeach
	<br><br>
	<table class="tablita" width="100%" rules="">
		<tr>
			<td  style="text-align: right; font-size: 15px; font-weight: bold;" >TOTAL GENERAL: ${{number_format($eltotal,2)}}</td>
		</tr>
	</table>
</body>
</html>

