<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>IVA por ventas</title>
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
	<div style="text-align: right;" class="fecha">FECHA: {{date("d/m/Y H:i:s a")}}</div>
	<div style="text-align:center; font-size: 20px; font-weight: bold;" class="titulo">TUNE-UP</div>
	<div style="text-align:center;" class="titulo">SERVICE</div>
	<div style="text-align:center;" class="titulo">REPORTE DE IVA POR VENTAS</div>
	<div style="text-align:center;" class="titulo">DEL {{$f1}} al {{$f2}}</div><br>
	<?php $netoiva=0;
		$sumiva=0;
		$totaliva=0.0; 
		$netosiniva=$sumasiva=$totalsiniva=$netos=$totales=0.0;
	foreach($cotizaciones as $c){
		if($c->tipo_documento==3){
			$netoiva=$netoiva+$c->subtotal;
			$sumiva=$sumiva+$c->iva;
			$totaliva=$totaliva+$c->total;
		}
		if($c->tipo_documento==2){
			$netosiniva=$netosiniva+$c->total;
		
		}
	}
	$iva=1+session('iva');

	$neto=$netosiniva/$iva;
	$eliva=$neto*session('iva');
	$sumasiva=$eliva+$sumiva;
	$netos=$netoiva+$neto;
	$totales=$netosiniva+$totaliva;
	?>

	<table width="100%">
		<tr>
			<td width="60%">RESUMEN</td>
			<td width="10%">NETO</td>
			<td width="10%">IVA</td>
			<td width="10%">1% RET</td>
			<td width="10%">TOTAL</td>
		</tr>		
	</table>
	<table width="100%" rules="all">
		<tr>
			<td style="text-align: left;" width="60%">CRÉDITOS FISCALES</td>
			<td style="text-align: right;" width="10%">${{number_format($netoiva,2)}}</td>
			<td style="text-align: right;" width="10%">${{number_format($sumiva,2)}}</td>
			<td style="text-align: right;" width="10%">$</td>
			<td style="text-align: right;" width="10%">${{number_format($totaliva,2)}}</td>
		</tr>		
	</table>
	<table width="100%" rules="all">
		<tr>
			<td style="text-align: left;" width="60%">FACTURAS</td>
			<td style="text-align: right;" width="10%">${{number_format($neto,2)}}</td>
			<td style="text-align: right;" width="10%">${{number_format($eliva,2)}}</td>
			<td style="text-align: right;" width="10%">$</td>
			<td style="text-align: right;" width="10%">${{number_format($netosiniva,2)}}</td>
		</tr>		
	</table>
	<table width="100%" rules="all">
		<tr>
			<td style="text-align: right;" width="60%">TOTALES</td>
			<td style="text-align: right;" width="10%">${{number_format($netos,2)}}</td>
			<td style="text-align: right;" width="10%">${{number_format($sumasiva,2)}}</td>
			<td style="text-align: right;" width="10%">$</td>
			<td style="text-align: right;" width="10%">${{number_format($totales,2)}}</td>
		</tr>		
	</table>
</body>
</html>

