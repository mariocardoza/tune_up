<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DteService;
use App\Cotizacione; // Importa el modelo de Compra
use App\Anulaciones; // Importa el modelo de Compra
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

        if($compra->estado_dte != "PROCESADO"){
            $compra->codigo_generacion = strtoupper(Uuid::uuid4()->toString());
            $compra->save();
        }
        if($compra->estado_dte != "PROCESADO"){
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

            
        }
        //dd($compra);

        // 2. Genera el JSON del DTE usando el modelo de compra
        $jsonDteGeneral = $this->dteService->generarDteGeneralJson($compra,$request->tipo);
        $arrayDteGeneral = json_decode($jsonDteGeneral, true);//Lo convertimos en array
        if($request->tipo == 1){//Es un DTE Normal (consumidor final)
            $compra->tipo_dte = '01';
            $compra->save();
            $datosFactura = $this->dteService->crearDteFactura($arrayDteGeneral);
            $version = 1;
        }
        if($request->tipo== 3){
            $compra->tipo_dte = '03';
            $compra->save();
            $datosFactura = $this->dteService->crearDteCredito($arrayDteGeneral);
            $version = 3;
        }
        if($request->tipo== 11){
            $compra->tipo_dte = '11';
            $compra->save();
            $datosFactura = $this->dteService->crearDteExportacion($arrayDteGeneral);
            $version = 1;
        }
        $dteFirmado = $this->dteService->firmarDTE($datosFactura);
        if(!$dteFirmado['status'] == 'OK'){
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo obtener el documento firmado.'
            ], 404);
        }
       // dd($dteFirmado);
        $datosFactura['firmaElectronica'] = $dteFirmado['body'];


        if($compra->estado_dte == "PROCESADO"){//Ya fue generado un DTE
            $datosFactura['selloRecibido'] = $compra->sello_generacion;
            $json_email = json_encode($datosFactura);
            $version = $request->tipo;
            $anulacion = false;
            $taller = Taller::first();
            $pdf = PDF::loadView('facturacion.dte', compact('compra','taller','version','anulacion'))->setPaper('letter', 'portrait');
            $pdfData = $pdf->output();

            // 4. Enviar el correo usando la clase Mailable
            if ($compra->cliente->correo != null &&  filter_var($compra->cliente->correo, FILTER_VALIDATE_EMAIL)) {
              Mail::to($compra->cliente->correo)->send(new FacturaAdjunta($pdfData, $json_email));
            }

            return response()->json([
                'success' => true,
                // Codifica el contenido binario para que sea seguro pasarlo por JSON
                'pdf_base64' => base64_encode($pdf->output()), 
                'filename' => $compra->codigo_generacion.'.pdf',
            ]);
        }

        
        // 3. Envía el DTE a la API del MH
        $respuestaApi = $this->dteService->enviarDte($dteFirmado['body'],$request->tipo,$compra->codigo_generacion,$version);
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
            $compra->estado_dte = "PROCESADO";
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
        $anulacion = false;
        $version = $request->tipo;
        $taller = Taller::first();
        $pdf = PDF::loadView('facturacion.dte', compact('compra','taller','version','anulacion'))->setPaper('letter', 'portrait');
        $pdfData = $pdf->output();

        // 3. Obtener el email del destinatario (ejemplo)
        if ($compra->cliente->correo != null &&  filter_var($compra->cliente->correo, FILTER_VALIDATE_EMAIL)) {
            Mail::to($compra->cliente->correo)->send(new FacturaAdjunta($pdfData, $json_email));
        }
        
        return response()->json([
            'success' => true,
            // Codifica el contenido binario para que sea seguro pasarlo por JSON
            'pdf_base64' => base64_encode($pdf->output()), 
            'filename' => $compra->codigo_generacion.'.pdf',
        ]);
    }

    public function InvalidarDTE(Request $request){
        $compra = Cotizacione::find($request->id);
        if (!$compra) {
            return response()->json([
                'status' => 'error',
                'message' => 'Compra no encontrada.'
            ], 404);
        }

        if ($compra->fecha_generacion->diffInHours(now()) > 24) {
            return response()->json([
                'status' => 'error',
                'message' => 'Han pasado mas de 24 horas, emitir nota de crédito'
            ], 404);
        }

        $codigoGenerado = strtoupper(Uuid::uuid4()->toString());
        $json = $this->dteService->jsonInvalidar($compra,$request,$codigoGenerado);

        $dteFirmado = $this->dteService->firmarDTE($json);
        if(!$dteFirmado['status'] == 'OK'){
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo obtener el documento firmado.'
            ], 404);
        }
        //dd($dteFirmado);
        $respuestaApi = $this->dteService->invalidarDTE($dteFirmado['body'],$codigoGenerado,"02");
        // 4. Maneja la respuesta de la API
        if (isset($respuestaApi['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $respuestaApi['response_body']['descripcionMsg'],
                'data' => $respuestaApi
            ], 400);
        }
        if ($respuestaApi['estado'] === 'PROCESADO') {
            $compra->estado_dte = null;
            /*$compra->codigo_generacion = null;
            $compra->sello_generacion = null;
            $compra->fecha_generacion = null;
            $compra->numero_control = null;*/
            $compra->save();
            $anulacion = new Anulaciones();
            $anulacion->estado ="PROCESADO";
            $anulacion->codigo_generacion =$respuestaApi['codigoGeneracion'];
            $anulacion->sello_recibido = $respuestaApi['selloRecibido'];
            $anulacion->fhProcesamiento = $respuestaApi['fhProcesamiento'];
            $anulacion->cotizacion_id = $compra->id;
            $anulacion->save();
        }
        $version = "22";
        $anulacion = true;
        $taller = Taller::first();
        $pdf = PDF::loadView('facturacion.dte', compact('compra','taller','version','anulacion'))->setPaper('letter', 'portrait');
        $pdfData = $pdf->output();

        // 3. Obtener el email del destinatario (ejemplo)
        if ($compra->cliente->correo != null &&  filter_var($compra->cliente->correo, FILTER_VALIDATE_EMAIL)) {
            Mail::to($compra->cliente->correo)->send(new FacturaAdjunta($pdfData, $json_email));
        }
        
        return response()->json([
            'success' => true,
            // Codifica el contenido binario para que sea seguro pasarlo por JSON
            'pdf_base64' => base64_encode($pdf->output()), 
            'filename' => $compra->codigo_generacion.'.pdf',
        ]);
    }

    public function generarEventoContingencia($dtesPendientes) {
        $evento = [
            'identificacion' => [
                'version' => 1,
                'ambiente' => config('facturacion.ambiente'),
                'codigoGeneracion' => strtoupper(Str::uuid()),
                'fTransmision' => date('Y-m-d'),
                'hTransmision' => date('H:i:s'),
            ],
            'emisor' => [
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
                "codEstableMH" => "M001",
                "codEstable" => "M001",
                "codPuntoVentaMH" => "P001",
                "codPuntoVenta" => "P001",
            ],
            'detalleDTE' => []
        ];

        foreach ($dtesPendientes as $index => $dte) {
            $evento['detalleDTE'][] = [
                'noItem' => $index + 1,
                'codigoGeneracion' => $dte->codigo_generacion,
                'tipoDoc' => $dte->tipo_dte,
            ];
        }

        return json_encode($evento);
    }


}