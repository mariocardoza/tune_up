<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DteService;
use App\Models\Compra; // Importa el modelo de Compra

class FacturacionController extends Controller
{
    protected $dteService;

    public function __construct(DteService $dteService)
    {
        $this->dteService = $dteService;
    }

    /**
     * Procesa la facturación para una compra existente.
     *
     * @param int $compraId
     * @return \Illuminate\Http\JsonResponse
     */
    public function generarFactura($compraId)
    {
        // 1. Busca la compra y sus detalles en la base de datos
        // Usa `with` para cargar la relación 'detalles' y evitar consultas N+1
        $compra = Compra::with('detalles', 'cliente')->find($compraId);

        if (!$compra) {
            return response()->json([
                'status' => 'error',
                'message' => 'Compra no encontrada.'
            ], 404);
        }

        // 2. Genera el JSON del DTE usando el modelo de compra
        $jsonDte = $this->dteService->generarDteJson($compra);

        // 3. Envía el DTE a la API del MH
        $respuestaApi = $this->dteService->enviarDte($jsonDte);

        // 4. Maneja la respuesta de la API
        if (isset($respuestaApi['error'])) {
            // ... (manejo de errores, igual que en el ejemplo anterior)
        }

        // 5. La factura fue aceptada. Guarda los datos de la respuesta en la compra.
        $compra->codigo_control = $respuestaApi['codigoControl'];
        $compra->sello_recepcion = $respuestaApi['selloRecepcion'];
        $compra->estado_dte = 'aceptado';
        $compra->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Factura electrónica enviada y aceptada.',
            'data' => $respuestaApi
        ]);
    }
}