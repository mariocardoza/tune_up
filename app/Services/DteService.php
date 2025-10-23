<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Carbon\Carbon;
use App\Cotizacione; // Asume que tienes un modelo llamado Compra
use App\TrabajoDetalle; // Y un modelo llamado CompraDetalle
use App\RepuestoDetalle; // Y un modelo llamado CompraDetalle

class DteService
{
    protected $client;

    public function __construct()
    {
        // ... (el constructor permanece igual)
        $this->client = new Client([
            'base_uri' => env('MH_API_URL'),
            'verify'   => false,
        ]);
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
                "ambiente" => env('MH_AMBIENTE', '01'), // Usa una variable de entorno
                "tipoDte" => "01",
                "numeroControl" => $compra->numero_control, // Usa el campo de tu tabla
                "codigoGeneracion" => $compra->codigo_generacion,
                "fecEmi" => $compra->fecha->format('Y-m-d'),
                "horEmi" => $compra->fecha->format('H:i:s'),
                "tipoModelo" => 1,
                "tipoOperacion" => 1
            ],
            // Mapea los datos del emisor
            "emisor" => [
                "nit" => env('MH_NIT'),
                // ...otros datos del emisor
            ],
            // Mapea los datos del receptor (tu cliente)
            "receptor" => [
                "nit" => $compra->cliente->nit, // Asumiendo una relación con un modelo Cliente
                // ...otros datos del receptor
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
    
    // El método enviarDte() y obtenerTokenAutenticacion() no cambian.
}