<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DteService;
use App\Cotizacione; // Importa el modelo de Compra
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaAdjunta;

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
    public function generarFactura(Request $request)
    {
        // 1. Busca la compra y sus detalles en la base de datos
        // Usa `with` para cargar la relación 'detalles' y evitar consultas N+1
        $compra = Cotizacione::find($request->id);

        if (!$compra) {
            return response()->json([
                'status' => 'error',
                'message' => 'Compra no encontrada.'
            ], 404);
        }

        // 2. Genera el JSON del DTE usando el modelo de compra
        $jsonDte = $this->dteService->generarDteJson($compra);
        //return $jsonDte;
        $array = json_decode($jsonDte, true);
        $datosFactura = $this->dteService->crearArray($array);
        //dd($datosFactura);
        $pdf = PDF::loadView('facturacion.dte', compact('datosFactura'))->setPaper('letter', 'portrait');
        $pdfData = $pdf->output();
        // 3. Obtener el email del destinatario (ejemplo)
        //$destinatarioEmail = $datosFactura['receptor']['correo']; 
        $destinatarioEmail = "h_rivas47@yahoo.com"; 
        
        // 4. Enviar el correo usando la clase Mailable
        Mail::to($destinatarioEmail)->send(new FacturaAdjunta($pdfData, $jsonDte));
        return response()->json(['message' => 'Factura enviada por correo con éxito!']);
        return response()->json([
        'success' => true,
        // Codifica el contenido binario para que sea seguro pasarlo por JSON
        'pdf_base64' => base64_encode($pdf->output()), 
        'filename' => 'Factura.pdf',
    ]);

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