@php
    //dd($datosFactura);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>FACTURA | </title>
    
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            margin: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 15px;
        }
        .header, .section {
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #ccc;
        }
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

    <div class="container">
        <div class="header">
            <div class="qr-code">
                {{-- Asumiendo que usas un paquete como simplesoftwareio/simple-qrcode --}}
                <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(80)->generate($datosFactura['codigoGeneracion'])) }}" 
     alt="QR Code Factura" 
     style="width: 80px; height: 80px;">
                
            </div>
            <div style="overflow: hidden;">
                <p class="text-center">DOCUMENTO TRIBUTARIO ELECTRÓNICO</p>
                <h2 class="text-center" style="margin-top: 0;">FACTURA </h2>
            </div>
        </div>
        
        <div class="section">
            <h3 style="margin-bottom: 5px;">DATOS DEL EMISOR </h3>
            <p style="margin: 0;"><strong>{{ $datosFactura['emisor']['nombre'] }}</strong> </p>
            <p style="margin: 0;">NIT: {{ $datosFactura['emisor']['nit'] }} | NRC: {{ $datosFactura['emisor']['nrc'] }} </p>
            <p style="margin: 0;">Sucursal: {{ $datosFactura['emisor']['sucursal'] }}</p>
            <p style="margin: 0;">Actividad Económica: {{ $datosFactura['emisor']['actividad'] }} </p>
            <p style="margin: 0;">Dirección: {{ $datosFactura['emisor']['direccion'] }} </p>
        </div>

        <div class="section">
            <h3 style="margin-bottom: 5px;">DATOS DE FACTURA </h3>
            <p style="margin: 0;">Código de Generación: {{ $datosFactura['codigoGeneracion'] }} </p>
            <p style="margin: 0;">Número de control: {{ $datosFactura['numeroControl'] }} </p>
            <p style="margin: 0;">Fecha y hora de Generación: {{ $datosFactura['fecEmi'] }} </p>
        </div>

        <div class="section">
            <h3 style="margin-bottom: 5px;">DATOS DEL RECEPTOR </h3>
            <p style="margin: 0;">Nombre: <strong>{{ $datosFactura['receptor']['nombre'] }}</strong> </p>
            <p style="margin: 0;">Tipo de Doc. de Identificación: {{ $datosFactura['receptor']['tipo_doc'] }} </p>
            <p style="margin: 0;">Número de Documento: {{ $datosFactura['receptor']['num_doc'] }} </p>
            <p style="margin: 0;">Dirección: {{ $datosFactura['receptor']['direccion'] }} </p>
            <p style="margin: 0;">Correo: {{ $datosFactura['receptor']['correo'] }} </p>
        </div>

        <h3 class="text-center">DETALLE DE FACTURA </h3>
        <table class="table-items">
            <thead>
                <tr>
                    <th style="width: 10%;">CANT.</th>
                    <th style="width: 50%;">DESCRIPCION </th>
                    <th style="width: 20%;">P. UNIT (Gravado)</th>
                    <th style="width: 20%;">TOTAL (Neto) </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datosFactura['cuerpoDocumento'] as $item)
                    <tr>
                        <td class="text-center">{{ number_format($item['cantidad'], 2) }} </td>
                        <td>
                          {{ $item['desProducto'] }} 
                        </td>
                        <td class="text-right">{{ number_format($item['precioUni'], 2) }}</td>
                        <td class="text-right">{{ number_format($item['montoItem'], 2) }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="total-summary">
            <tr>
                <td>Suma Ventas Gravadas:</td>
                <td class="text-right">{{ number_format($datosFactura['resumen']['totalGravada'], 2) }} </td>
            </tr>
            <tr>
                <td>Sub-Total:</td>
                <td class="text-right">{{ number_format($datosFactura['resumen']['subTotal'], 2) }} </td>
            </tr>
            <tr>
                <td>Monto Total de la Operación:</td>
                <td class="text-right">{{ number_format($datosFactura['resumen']['totalGravada'], 2) }} </td>
            </tr>
            <tr>
                <td>Total a pagar:</td>
                <td class="text-right">{{ number_format($datosFactura['resumen']['totalGravada'], 2) }} </td>
            </tr>
        </table>
        
        <p>TOTAL EN LETRAS: **{{ $datosFactura['resumen']['total_en_letras'] }} </p>
        
        <div class="section" style="border-bottom: none; border-top: 1px dashed #000; padding-top: 10px;">
            <p style="margin: 0;">Forma pago: **{{ $datosFactura['forma_pago'] }}** </p>
        </div>
    </div>
    <script type="text/php">
    if (isset($pdf)) {
        $pdf->page_text(500, 780, "Página {PAGE_NUM} de {PAGE_COUNT}", null, 10, array(0,0,0));
    }
</script>
</body>
</html>