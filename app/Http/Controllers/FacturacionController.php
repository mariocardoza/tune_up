<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DteService;
use App\Cotizacione; // Importa el modelo de Compra
use App\Taller; // Importa el modelo de Compra
use App\Dte; // Importa el modelo de Compra
use PDF;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaAdjunta;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;

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

        //if($compra->codigo_generacion == null){
            $compra->codigo_generacion = strtoupper(Uuid::uuid4()->toString());
            $compra->save();
        //}
        //if($compra->numero_control == null){
            $anioActual = date('Y');

            $siguienteCorrelativoAnioActual = DB::table('dtes')
                ->selectRaw('MAX(correlativo) + 1 AS siguiente_correlativo')
                ->where('anio', $anioActual)
                ->where('tipoDte', $request->tipo)
                ->value('siguiente_correlativo'); // Obtiene directamente el valor

            $siguienteCorrelativo = $siguienteCorrelativoAnioActual ?? 1;
            $dte = $this->dteService->generarNumeroControl(str_pad($request->tipo, 2, "0", STR_PAD_LEFT),"M001P001",$siguienteCorrelativo);
            $compra->numero_control = $dte;
            $compra->save();

            
        //}
        //dd($compra);

        // 2. Genera el JSON del DTE usando el modelo de compra
        $jsonDteGeneral = $this->dteService->generarDteGeneralJson($compra,$request->tipo);
        $arrayDteGeneral = json_decode($jsonDteGeneral, true);//Lo convertimos en array
        if($request->tipo == 1){//Es un DTE Normal (consumidor final)
            $datosFactura = $this->dteService->crearDteFactura($arrayDteGeneral);
        }
        $dteFirmado = $this->dteService->firmarDTE($datosFactura);
        if(!$dteFirmado['status'] == 'OK'){
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo obtener el documento firmado.'
            ], 404);
        }

        $datosFactura['firmaElectronica'] = $dteFirmado['body'];
        
        // 3. Envía el DTE a la API del MH
        $respuestaApi = $this->dteService->enviarDte($dteFirmado['body'],$request->tipo,$compra->codigo_generacion);
        // 4. Maneja la respuesta de la API
        if (isset($respuestaApi['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $respuestaApi['response_body']['descripcionMsg'],
                'data' => $respuestaApi
            ], 400);
        }
        if ($respuestaApi['estado'] === 'PROCESADO') {
    
            // 2. Convertir la fecha al formato de base de datos (MySQL)
            $fechaMh = Carbon::createFromFormat('d/m/Y H:i:s', $respuestaApi['fhProcesamiento']);
            $compra->codigo_generacion = $respuestaApi['codigoGeneracion'];
            $compra->sello_generacion = $respuestaApi['selloRecibido'];
            $compra->fecha_generacion = $fechaMh;
            $compra->fecha_procesamiento = $respuestaApi['fhProcesamiento'];
            $compra->save();

            $nuevoC = Dte::create([
                'cotizacion_id' => $compra->id,
                'correlativo' => $siguienteCorrelativo,
                'anio' => $anioActual,
                'tipoDte' => $request->tipo,
                'codigoGeneracion' => $respuestaApi['codigoGeneracion']
            ]);

            $datosFactura['selloRecibido'] = $respuestaApi['selloRecibido'];
        }

        $json_email = json_encode($datosFactura);
        // 5. La factura fue aceptada. Guarda los datos de la respuesta en la compra.

        $taller = Taller::first();
        $pdf = PDF::loadView('facturacion.dte', compact('compra','taller'))->setPaper('letter', 'portrait');
        $pdfData = $pdf->output();

        // 3. Obtener el email del destinatario (ejemplo)
        //$destinatarioEmail = $datosFactura['receptor']['correo']; 
        //$destinatarioEmail = "h_rivas47@yahoo.com"; 
        $destinatarioEmail = "mariokr.rocker@gmail.com"; 
        //$destinatarioCC = "h_rivas47@yahoo.com"; 
        
        // 4. Enviar el correo usando la clase Mailable
        Mail::to($destinatarioEmail)->send(new FacturaAdjunta($pdfData, $json_email));
            
        //return response()->json(['message' => 'Factura enviada por correo con éxito!']);
        
        return response()->json([
            'success' => true,
            // Codifica el contenido binario para que sea seguro pasarlo por JSON
            'pdf_base64' => base64_encode($pdf->output()), 
            'filename' => 'Factura.pdf',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Factura electrónica enviada y aceptada.',
            'data' => $respuestaApi
        ]);
    }
}