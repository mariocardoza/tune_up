<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Cotizacione; // Asume que tienes un modelo llamado Compra
use App\TrabajoDetalle; // Y un modelo llamado CompraDetalle
use App\RepuestoDetalle; // Y un modelo llamado CompraDetalle

class DteService
{
    protected $client;

    public function __construct(Client $guzzleClient)
    {
       $this->client = $guzzleClient;
    }

    /**
     * Genera el JSON del DTE a partir de los modelos de Laravel.
     *
     * @param Compra $compra
     * @return string JSON del DTE
     */
    public function generarDteJson(Cotizacione $compra)
    {
        // Mapea los datos de la tabla 'compra' a la sección 'identificacion' del DTE
        $dte = [
            "identificacion" => [
                "version" => 1,
                "ambiente" => env('MH_AMBIENTE', '00'), // Usa una variable de entorno
                "tipoDte" => "01",
                "numeroControl" => $compra->numero_control, // Usa el campo de tu tabla
                "codigoGeneracion" => $compra->codigo_generacion,//$compra->codigo_generacion,
                "fecEmi" => $compra->fecha->format('Y-m-d'),
                "horEmi" => $compra->fecha->format('H:i:s'),
                "tipoModelo" => 1,
                "tipoOperacion" => 1,
                "tipoMoneda" => "USD",
                "motivoContin" => null,
                'ventaTercero' => null,
                'tipoContingencia' => null
            ],
            "emisor" => [
                "nit" => env('MH_NIT'),
                "nombre" => "TUNEUP SERVICE",
                "nombreComercial" => "TUNEUP SERVICE",
                "nrc" => "1314382",
                "codActividad" => "45201",
                "descActividad" => "Reparación mecánica de vehículos automotores",
                "tipoEstablecimiento" => "01",
                "sucursal" => "Central",
                "correo" => "tuneup@gmail.com",
                "direccion" => [
                    "departamento" => "06",
                    "municipio" => "14",
                    "complemento" =>"Calle San Carlos, Colonia laico 1004, final 17 av norte"
                ],
                "telefono" => "77303565",
                "codEstableMH" => null,
                "codEstable" => null,
                "codPuntoVentaMH" => null,
                "codPuntoVenta" => null,
            ],
            // Mapea los datos del receptor (tu cliente)
            "receptor" => [
                "numDocumento" => $compra->cliente->tipo_documento == '13' ? $compra->cliente->dui : $compra->cliente->nit, // Asumiendo una relación con un modelo Cliente
                "nrc" => $compra->cliente->reg_iva == '' ? null : preg_replace('/[^0-9]/', '', $compra->cliente->reg_iva), // Asumiendo una relación con un modelo Cliente
                "nombre" => $compra->cliente->nombre, // Asumiendo una relación con un modelo Cliente
                "direccion" => $compra->cliente->direccion == '' ? null : $compra->cliente->direccion, 
                "correo" => $compra->cliente->correo ?? null, // Asumiendo una relación con un modelo Cliente
                "telefono" => $compra->cliente->telefono == '' ? '00000000' : $compra->cliente->telefono, // Asumiendo una relación con un modelo Cliente
                "tipo_documento" => $compra->cliente->tipo_documento,
                "descActividad" => null,
                "codActividad" => null
            ],
        ];

        // Mapea los datos de la tabla 'compradetalle' a 'cuerpoDocumento'
        $items = [];
        $totalGravado = 0;
        foreach ($compra->repuestodetalle as $detalle) { // 'detalles' es la relación en el modelo Compra
            $items[] = [
                "numItem" => $detalle->repuesto->id,
                "codProducto" => $detalle->repuesto->codigo,
                "desProducto" => $detalle->repuesto->nombre,
                "cantidad" => $detalle->cantidad,
                "precioUni" => $detalle->precio,
                "montoItem" => $detalle->precio*$detalle->cantidad,
                "uniMedida" => 99,
                "ivaItem" => $this->obtenerIvaItem($detalle->precio*$detalle->cantidad)
            ];
            $totalGravado += $detalle->precio*$detalle->cantidad;
        }
        foreach ($compra->trabajodetalle as $detalle) { // 'detalles' es la relación en el modelo Compra
            $items[] = [
                "numItem" => $detalle->trabajo->id,
                "codProducto" => $detalle->trabajo->codigo,
                "desProducto" => $detalle->trabajo->nombre,
                "cantidad" => 1,
                "precioUni" => $detalle->precio,
                "montoItem" => $detalle->precio,
                "uniMedida"=> 99,
                "ivaItem" => $this->obtenerIvaItem($detalle->precio)
            ];
            $totalGravado += $detalle->precio;
        }

        $dte["cuerpoDocumento"] = $items;

        // Mapea los totales a la sección 'resumen'
        $dte["resumen"] = [
            "totalIva" => $compra->iva,
            "totalDescuento" => 0,
            "totalGravada" => $totalGravado,
            "subTotal" => $totalGravado,
            "montoTotal" => $totalGravado,
            "totalIva" => $this->obtenerIvaItem($totalGravado),
            "pagos" => [
                [
                "codigo" => "02",
                "montoPago" => round($totalGravado,2),
                "referencia" => null,
                "plazo" => "01",
                "periodo" => null,
                ]
            ]
        ];
        
        return json_encode($dte);
    }

    public function enviarDte($jsonDte)
    {
        dd($jsonDte);
    }

    public function obtenerIvaItem(float $montoTotal): float {
        // Tasa de IVA en El Salvador (13%)
        $tasaIva = 0.13;
        // Factor para desglosar (1 + 0.13 = 1.13)
        $factorDesglose = 1 + $tasaIva;

        // 1. Calcular la Base Imponible (Monto SIN IVA)
        $baseImponible = $montoTotal / $factorDesglose;

        // 2. Calcular el Monto del IVA (Diferencia entre Total y Base Imponible)
        $ivaItem = $montoTotal - $baseImponible;

        // Retorna solo el valor del IVA, redondeado
        return round($ivaItem, 2);
    }

    public function firmarDTE($jsonDte) : array
    {
        $url = env('URL_FIRMADOR');
        $nit = env('MH_NIT');
        $password = env('PW_PRIVATE');

        $payload = [
            'nit'           => $nit,
            'dteJson'       => $jsonDte, // Aquí va el DTE completo
            'passwordPri' => $password,
        ];

        if (!$url || !$nit || !$password) {
             Log::error('DTE Signer Configuration Missing', [
                'url' => (bool)$url,
                'nit' => (bool)$nit,
                'pass' => (bool)$password
             ]);
             return [
                 'status' => 'ERROR',
                 'mensaje' => 'Configuración de credenciales del firmador DTE incompleta (.env).',
                 'code' => '500'
             ];
        }

        try {
            // 4. Enviar la petición POST
            $response = $this->client->request('POST', $url, [
                // 'json' convierte el array PHP a JSON y lo establece como cuerpo de la petición.
                'json' => $payload, 
                // Opcional: Configuración de timeout si el firmador tarda mucho
                // 'timeout' => 10.0, 
            ]);

            // Guzzle devuelve la respuesta como un objeto PSR-7.
            // Obtenemos el cuerpo y lo decodificamos.
            $body = (string) $response->getBody();
            return json_decode($body, true);

        } catch (ConnectException $e) {
            // Manejo de errores de conexión (ej. Docker no está corriendo, puerto equivocado)
            Log::error('DTE Signer Connection Error', ['error' => $e->getMessage(), 'url' => $url]);
            return [
                'status' => 'ERROR',
                'mensaje' => 'Error de conexión con el firmador DTE (Docker Down, Timeout, etc.): ' . $e->getMessage(),
                'code' => '503'
            ];
        } catch (RequestException $e) {
            // Manejo de errores HTTP (ej. 404, 500 del servidor del firmador)
            $response = $e->getResponse();
            $body = $response ? (string) $response->getBody() : 'Respuesta vacía';
            
            Log::error('DTE Signer HTTP Error', ['status' => $response ? $response->getStatusCode() : 'N/A', 'body' => $body]);

            return [
                'status' => 'ERROR',
                'mensaje' => 'Error HTTP del firmador. Código: ' . ($response ? $response->getStatusCode() : 'Desconocido'),
                'code' => $response ? $response->getStatusCode() : '500'
            ];
        }

    }

    public function crearArray($facturaJson){
        $datosFactura = [
        // --- Mapeo de Identificación y Emisor ---
        'documentoRelacionado' => null,
        'extension' => null,
        'ventaTercero' => $facturaJson['identificacion']['ventaTercero'],
        'identificacion' => [
            'ambiente' => $facturaJson['identificacion']['ambiente'],
            'numeroControl' => $facturaJson['identificacion']['numeroControl'],
            'codigoGeneracion' => $facturaJson['identificacion']['codigoGeneracion'],
            'tipoModelo' => $facturaJson['identificacion']['tipoModelo'],
            'tipoOperacion' => $facturaJson['identificacion']['tipoOperacion'],
            'tipoMoneda' => $facturaJson['identificacion']['tipoMoneda'],
            'version' => $facturaJson['identificacion']['version'],
            'tipoDte' => $facturaJson['identificacion']['tipoDte'],
            'fecEmi' => $facturaJson['identificacion']['fecEmi'],
            'horEmi' => $facturaJson['identificacion']['horEmi'],
            'motivoContin' => $facturaJson['identificacion']['motivoContin'],
            'tipoContingencia' => $facturaJson['identificacion']['tipoContingencia'],
        ],
        'emisor' => [
            'nombre' => $facturaJson['emisor']['nombre'],
            'nombreComercial' => $facturaJson['emisor']['nombre'],
            'nit' => $facturaJson['emisor']['nit'],
            'nrc' => $facturaJson['emisor']['nrc'],
            'codActividad' => $facturaJson['emisor']['codActividad'],
            'descActividad' => $facturaJson['emisor']['descActividad'], // Revisar si este campo no está vacío en el JSON real
            'direccion' => $facturaJson['emisor']['direccion'],
            'telefono' => $facturaJson['emisor']['telefono'],
            'correo' => $facturaJson['emisor']['correo'],
            'tipoEstablecimiento' => '02',
            'codEstableMH' => $facturaJson['emisor']['codEstableMH'],
            'codEstable' => $facturaJson['emisor']['codEstable'],
            'codPuntoVentaMH' => $facturaJson['emisor']['codPuntoVentaMH'],
            'codPuntoVenta' => $facturaJson['emisor']['codPuntoVenta'],
        ],
        
        // --- Mapeo de Receptor ---
        'receptor' => [
            'nombre' => $facturaJson['receptor']['nombre'],
            'tipoDocumento' => $facturaJson['receptor']['tipo_documento'], // Asume un valor basado en el tipo de documento 614...
            'numDocumento' => $facturaJson['receptor']['numDocumento'],
            // El JSON no incluye explícitamente la dirección completa del receptor, usa lo disponible
            'direccion' => $facturaJson['receptor']['direccion'], 
            'correo' => $facturaJson['receptor']['correo'],
            'telefono' => $facturaJson['receptor']['telefono'],
            'nrc' => $facturaJson['receptor']['nrc'],
            'codActividad' => $facturaJson['receptor']['codActividad'],
            'descActividad' => $facturaJson['receptor']['descActividad'],
        ],

        // --- Mapeo de Items (Cuerpo del Documento) ---
        'cuerpoDocumento' => collect($facturaJson['cuerpoDocumento'])->map(function ($item, $key) {
            return [
                'numItem' => $key + 1,
                'tipoItem' => 1,
                'numeroDocumento' => null,
                'codigo' => $item['codProducto'],
                'cantidad' => $item['cantidad'],
                'uniMedida' => $item['uniMedida'],
                'descripcion' => $item['desProducto'],
                'precioUni' => $item['precioUni'],
                'ventaGravada' => $item['montoItem'],
                'psv' => $item['montoItem'],
                'montoDescu' => 0.0,
                'ventaNoSuj' => 0.0,
                'ventaExenta' => 0.0,
                'tributos' => null,
                'codTributo' => null,
                'noGravado' => 0.0,
                'ivaItem' => $item['ivaItem']
            ];
        })->all(),

        // --- Mapeo de Totales ---
        'resumen' => [
            'totalNoSuj' => 0.0,
            'totalExenta' => 0.0,
            'descuNoSuj' => 0.0,
            'descuExenta' => 0.0,
            'descuGravada' => 0.0,
            'porcentajeDescuento' => 0.0,
            'totalDescu' => 0.0,
            'tributos' => null,
            'totalNoGravado' => 0.0,
            'reteRenta' => 0.0,
            'ivaRete1' => 0.0,
            'condicionOperacion' => 1,
            'numPagoElectronico' => null,
            'saldoFavor' => 0.0,
            'pagos' => $facturaJson['resumen']['pagos'],
            'totalIva' => $facturaJson['resumen']['totalIva'],
            'totalGravada' => $facturaJson['resumen']['totalGravada'],
            'subTotalVentas' => $facturaJson['resumen']['totalGravada'],
            'totalPagar' => $facturaJson['resumen']['totalGravada'],
            'montoTotalOperacion' => $facturaJson['resumen']['totalGravada'],
            'subTotal' => $facturaJson['resumen']['subTotal'],
            'totalGravada' => $facturaJson['resumen']['totalGravada'], // En este caso, son iguales
            'totalGravada' => $facturaJson['resumen']['totalGravada'], // Total a pagar
            'totalLetras' => numaletras($facturaJson['resumen']['totalGravada']), // Debes usar la función helper aquí
        ],
        'apendice' => null,
        'otrosDocumentos' => null
        
        // --- Mapeo de Información Adicional ---
        //'forma_pago' => 'POR DEFINIR', // Este dato no está en el JSON de ejemplo
        //'codigo_vendedor' => 'POR DEFINIR', // Este dato no está en el JSON de ejemplo
    ];
        return $datosFactura;
    }

    public function generarNumeroControl(string $tipoDte, string $codigoSucursal, int $ultimoCorrelativo): string 
    {
        // El correlativo debe ser de 15 dígitos con ceros a la izquierda.
        $nuevoCorrelativo = $ultimoCorrelativo + 1;
        $correlativo15Digitos = str_pad((string)$nuevoCorrelativo, 15, '0', STR_PAD_LEFT);
        
        // Concatenación para formar el número de control
        $numeroControl = "DTE-". $tipoDte . '-'.$codigoSucursal.'-' . $correlativo15Digitos;
        
        return $numeroControl;
    }
    
    // El método enviarDte() y obtenerTokenAutenticacion() no cambian.
}