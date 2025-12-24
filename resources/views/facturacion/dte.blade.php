@php
    //dd($datosFactura);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FACTURA | </title>
    
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .uppercase { text-transform: uppercase; }
        .font-bold { font-weight: bold; }
        
        /* Contenedor principal */
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .header-table td { vertical-align: top; }

        /* Cuadros Emisor/Receptor con bordes redondeados */
        .box-container { width: 100%; margin-top: 20px; }
        .box {
            border: 1px solid #000;
            border-radius: 15px; /* DomPDF requiere versiones recientes para esto */
            padding: 15px;
            position: relative;
            width: 45%;
            float: left;
            min-height: 180px;
        }
        .box-receptor { float: right; }
        
        /* El título que "rompe" el borde */
        .box-title {
            position: absolute;
            top: -10px;
            left: 50%;
            margin-left: -35px; /* Ajuste manual para centrar */
            background-color: white;
            padding: 0 10px;
            font-weight: bold;
            font-size: 12px;
        }

        /* Estilos para la tabla de productos */
        .tabla-detalle {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            border: 1px solid #000;
        }
        .tabla-detalle th {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            background-color: #f8f9fa;
            text-transform: uppercase;
        }
        .tabla-detalle td {
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            padding: 5px;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Estilos para el cuadro de totales */
        .totales-container {
            width: 100%;
            margin-top: 10px;
        }
        .cuadro-totales {
            width: 35%; /* Ajusta el ancho según necesites */
            float: left;
            border: 1px solid #000;
            padding: 8px;
            border-radius: 5px;
        }
        .totales-row {
            display: block;
            width: 100%;
            margin-bottom: 3px;
        }
        .total-label { display: inline-block; width: 65%; }
        .total-value { display: inline-block; width: 30%; text-align: right; }

        .letras-monto {
            margin-top: 10px;
            font-weight: bold;
            clear: both;
        }

        .clear { clear: both; }
        .bg-highlight { background-color: #e0f2ff; padding: 2px; }
        .mt-2 { margin-top: 10px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .details-grid {
            display: table;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
        }
        .details-grid .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 15px;
        }
        .table-items th, .table-items td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        .table-items th {
            background-color: #f2f2f2;
            text-align: center;
        }
        .total-summary {
            width: 40%;
            /*margin-left: auto;*/
            border: 1px solid #000;
        }
        .total-summary td {
            padding: 5px;
            font-size: 10pt;
        }
        .qr-code {
            float: left;
            margin-right: 20px;
        }
        @page {
            margin-bottom: 30mm; /* Deja espacio en la parte inferior para el pie de página */
        }

        /* Estilo para el contenedor del pie de página */
        .footer {
            position: fixed;
            bottom: -30mm; /* Coloca el pie 20mm desde el fondo de la página */
            left: 0;
            right: 0;
            height: 30mm;
            text-align: center;
            font-size: 8pt;
            color: #000;
        }
    </style>
</head>
<body>

    <div class="">
    
        <div class="text-center uppercase font-bold">
            <h6 class="mb-0 font-weight-bold">DOCUMENTO TRIBUTARIO ELECTRÓNICO </h6>
            <p><b style="text-align: right;">Ver. {{$version}}</b></p>
            @if($compra->tipo_documento == 2)
                <h5 class="font-weight-bold mt-2">FACTURA</h5>
            @elseif($compra->tipo_documento == 3)
                <h5 class="font-weight-bold mt-2">COMPROBANTE DE CRÉDITO FISCAL</h5>
            @else
                <h5 class="font-weight-bold mt-2">FACTURA DE EXPORTACIÓN</h5>
            @endif
        </div>

        <table class="header-table">
            <tr>
                <td width="40%">
                    <strong>Código de Generación:</strong> {{ $compra->codigo_generacion }}<br>
                    <strong>Número de Control:</strong> {{ $compra->numero_control }}<br>
                    <strong>Sello de Recepción:</strong> {{ $compra->sello_generacion }}
                </td>
                <td width="20%" class="text-center">
                    <div style="border: 1px solid #ccc; width: 80px; height: 80px; margin: 0 auto;">
                        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->generate($compra->codigo_generacion)) }}" 
     alt="QR Code Factura" 
     style="width: 80px; height: 80px;">
                    </div>
                </td>
                <td width="40%" class="text-right">
                    <strong>Modelo de Facturación:</strong> Previo<br>
                    <strong>Tipo de Transmisión:</strong> Normal<br>
                    <strong>Fecha y Hora de Generación:</strong> {{ $compra->fecha_procesamiento }}
                </td>
            </tr>
        </table>

        <div class="box-container">
            <div class="box">
                <span class="box-title">{{$compra->tipo_documento == 4 ? "(Exportador)":null }}  EMISOR</span>
                <strong>Nombre o razón social:</strong> {{$taller->nombre}}<br>
                <strong>NIT:</strong> {{$taller->nit}}<br>
                <strong>NRC:</strong> {{$taller->nrc}}<br>
                <strong>Actividad económica:</strong> {{$taller->actividad_economica}} <br>
                <strong>Dirección:</strong> {{ $taller->direccion }}<br>
                <strong>Teléfono:</strong> {{$taller->celular}}<br>
                <strong>Email:</strong> {{$taller->email}}<br>
                @if($compra->tipo_documento == 4)
                    <strong>Recinto Fiscal:</strong> 01<br>
                    <strong>Régimen de exportación:</strong> EX-1.1000.000
                @endif
            </div>

            <div class="box box-receptor">
                <span class="box-title">RECEPTOR</span>
                <strong>Nombre o razón social:</strong> {{ $compra->cliente->nombre }}<br>
                <strong>Tipo de Documento:</strong> {{$compra->cliente->documento->nombre_documento}}<br>
                <strong>Número de Documento:</strong> {{$compra->cliente->numero_documento}}<br>
                <strong>NRC:</strong> {{$compra->cliente->reg_iva}}<br>
                <strong>Dirección:</strong> {{ $compra->cliente->direccion ?? '-' }} , {{$compra->cliente->municipio->nombre.', '.$compra->cliente->municipio->departamento->nombre}}<br>
                <strong>Correo electrónico:</strong> {{ $compra->cliente->email ?? '-' }}<br>
                <strong>Número de teléfono:</strong> {{ $compra->cliente->telefono ?? '00000000' }}<br>
                @if($compra->tipo_documento == 4)
                    <strong>Pais destino:</strong> {{ $compra->cliente->pais->nombre }}
                @endif
            </div>
            <div class="clear"></div>
        </div>

    </div>

    <div class="container">

        <div class="text-center font-bold mt-2" style="width: 100%; text-decoration: underline;">
            DETALLE DE FACTURA
        </div>


        <table class="tabla-detalle">
            <thead>
                <tr>
                    <th style="width: 10%;">CANT.</th>
                    <th style="width: 50%;">DESCRIPCION </th>
                    <th style="width: 20%;">P. UNIT (Gravado)</th>
                    <th style="width: 20%;">TOTAL (Neto) </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($compra->repuestodetalle as $detalle)
                    <tr>
                        <td class="text-center">{{ number_format($detalle->cantidad, 2) }} </td>
                        <td>
                          {{ $detalle->repuesto->nombre }} 
                        </td>
                        <td class="text-right">{{ number_format($detalle->precio, 2) }}</td>
                        <td class="text-right">{{ number_format($detalle->precio*$detalle->cantidad, 2) }} </td>
                    </tr>
                @endforeach
                @foreach ($compra->trabajodetalle as $detalle)
                    <tr>
                        <td class="text-center">{{ number_format(1, 2) }} </td>
                        <td>
                          {{ $detalle->trabajo->nombre }} 
                        </td>
                        <td class="text-right">{{ number_format($detalle->precio, 2) }}</td>
                        <td class="text-right">{{ number_format($detalle->precio, 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totales-container">
            <div class="cuadro-totales">
                <div class="totales-row">
                    <span class="total-label">Suma Ventas Gravadas:</span>
                    <span class="total-value">{{ number_format($compra->subtotal, 2) }}</span>
                </div>
                <div class="totales-row">
                    <span class="total-label">Sub-Total:</span>
                    <span class="total-value">{{ number_format($compra->subtotal, 2) }}</span>
                </div>
                @if($compra->tipo_documento == 3)
                    <div class="totales-row">
                        <span class="total-label">IVA:</span>
                        <span class="total-value">{{ number_format($compra->iva, 2) }}</span>
                    </div>
                @endif
                <div class="totales-row">
                    <span class="total-label">SubTotal:</span>
                    <span class="total-value">{{ number_format($compra->subtotal, 2) }}</span>
                </div>
                @if($compra->tipo_documento == 3)
                    <div class="totales-row">
                        <span class="total-label">IVA Retenido:</span>
                        <span class="total-value">{{ number_format($compra->iva_r, 2) }}</span>
                    </div>
                @endif
                <div class="totales-row font-bold">
                    <span class="total-label">Monto total de la Operación: </span>
                    <span class="total-value">{{ number_format($compra->total, 2) }}</span>
                </div>
                <div class="totales-row font-bold">
                    <span class="total-label">Total a pagar:</span>
                    <span class="total-value">{{ number_format($compra->total, 2) }}</span>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        @if($compra->tipo_documento == 4)
        <div class="totales-container" style="width: 100%">
            <div class="cuadro-totales" style="width: 100%">
                <div class="totales-row">
                    <span class="total-label">Valor en letras:</span>
                    <span class="total-value">{{ numaletras($compra->total) }}</span>
                </div>
                <div class="totales-row">
                    <span class="total-label">Condición Operación:</span>
                    <span class="total-value">CONTADO</span>
                </div>
            
                <div class="totales-row">
                    <span class="total-label">Descripción Incoterms::</span>
                    <span class="total-value">DDP-Entrega con impuestos pagados</span>
                </div>
                
            </div>
        </div>
        @endif
        @if($compra->tipo_documento == 2)
        <div class="letras-monto">
            TOTAL EN LETRAS: **{{ numaletras($compra->total) }}**
        </div>
        
        <hr style="border-top: 1px dashed #000; margin-top: 10px;">
        <div class="font-bold">Forma pago: ** Contado **</div>
        @endif
    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(500, 780, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
    }
</script>
</body>
</html>