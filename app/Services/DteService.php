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
                "codigoGeneracion" => "codigoGeneracion",//$compra->codigo_generacion,
                "fecEmi" => $compra->fecha->format('Y-m-d'),
                "horEmi" => $compra->fecha->format('H:i:s'),
                "tipoModelo" => 1,
                "tipoOperacion" => 1,
                "tipoMoneda" => "USD"
            ],
            "emisor" => [
                "nit" => env('MH_NIT'),
                "nombre" => "TUNE-UP SERVICE",
                "nombreComercial" => "TUNE-UP SERVICE",
                "nrc" => "131438-2",
                "codActividad" => "",
                "descActividad" => "Reparación de Vehiculos y Maquinaria",
                "tipoEstablecimiento" => "01",
                "sucursal" => "Central",
                "correo" => "tuneup@gmail.com",
                "direccion" => "Calle San Carlos, Colonia laico #1004, final 17 av norte",
                "codEstableMH" => "",
                "codEstable" => "",
                "codPuntoVentaMH" => "",
                "codPuntoVenta" => "",
                // ...otros datos del emisor
            ],
            // Mapea los datos del receptor (tu cliente)
            "receptor" => [
                "nit" => $compra->cliente->nit, // Asumiendo una relación con un modelo Cliente
                "nrc" => $compra->cliente->reg_iva, // Asumiendo una relación con un modelo Cliente
                "nombre" => $compra->cliente->nombre, // Asumiendo una relación con un modelo Cliente
                "direccion" => $compra->cliente->direccion, // Asumiendo una relación con un modelo Cliente
                "correo" => $compra->cliente->correo, // Asumiendo una relación con un modelo Cliente
                "telefono" => $compra->cliente->telefono, // Asumiendo una relación con un modelo Cliente
                "tipo_documento" => "13",
                "nombreComercial" => "",
                "descActividad" => "",
                "codActividad" => ""
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
                "uniMedida"=> 99
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
            "montoTotal" => $compra->total,
        ];
        
        $dte["extension"] = [
                "nombreResponsable" => $compra->cliente->nombre,
                "docResponsable" => $compra->cliente->nit
        ];

        return json_encode($dte);
    }

    public function enviarDte($jsonDte)
    {
        dd($jsonDte);
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
        'codigoGeneracion' => $facturaJson['identificacion']['codigoGeneracion'],
        'numeroControl' => $facturaJson['identificacion']['numeroControl'],
        'fecEmi' => $facturaJson['identificacion']['fecEmi'] . ' ' . $facturaJson['identificacion']['horEmi'],
        'emisor' => [
            'nombre' => $facturaJson['emisor']['nombre'],
            'nit' => $facturaJson['emisor']['nit'],
            'nrc' => $facturaJson['emisor']['nrc'],
            'sucursal' => $facturaJson['emisor']['sucursal'],
            'actividad' => $facturaJson['emisor']['descActividad'], // Revisar si este campo no está vacío en el JSON real
            'direccion' => $facturaJson['emisor']['direccion'],
        ],
        
        // --- Mapeo de Receptor ---
        'receptor' => [
            'nombre' => $facturaJson['receptor']['nombre'],
            'tipo_doc' => 'NIT/NRC', // Asume un valor basado en el tipo de documento 614...
            'num_doc' => $facturaJson['receptor']['nit'],
            // El JSON no incluye explícitamente la dirección completa del receptor, usa lo disponible
            'direccion' => $facturaJson['receptor']['direccion'], 
            'correo' => $facturaJson['receptor']['correo'],
        ],

        // --- Mapeo de Items (Cuerpo del Documento) ---
        'cuerpoDocumento' => collect($facturaJson['cuerpoDocumento'])->map(function ($item) {
            return [
                'cantidad' => $item['cantidad'],
                'desProducto' => $item['desProducto'],
                'precioUni' => $item['precioUni'],
                'montoItem' => $item['montoItem'],
            ];
        })->all(),

        // --- Mapeo de Totales ---
        'resumen' => [
            'totalGravada' => $facturaJson['resumen']['totalGravada'],
            'subTotal' => $facturaJson['resumen']['subTotal'],
            'totalGravada' => $facturaJson['resumen']['totalGravada'], // En este caso, son iguales
            'totalGravada' => $facturaJson['resumen']['totalGravada'], // Total a pagar
            'total_en_letras' => numaletras($facturaJson['resumen']['totalGravada']), // Debes usar la función helper aquí
        ],
        
        // --- Mapeo de Información Adicional ---
        'forma_pago' => 'POR DEFINIR', // Este dato no está en el JSON de ejemplo
        'codigo_vendedor' => 'POR DEFINIR', // Este dato no está en el JSON de ejemplo
    ];
        return $datosFactura;
    }
    
    // El método enviarDte() y obtenerTokenAutenticacion() no cambian.
}