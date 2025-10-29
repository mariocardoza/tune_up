<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FacturaAdjunta extends Mailable
{
    use Queueable, SerializesModels;
    public $pdfData;
    public $jsonData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdfData, $jsonData)
    {
        // 2. Asignar las propiedades dentro del constructor (PHP 7.4)
        $this->pdfData = $pdfData;
        $this->jsonData = $jsonData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Tu Factura Electrónica DTE y JSON Adjuntos')
            ->view('emails.factura') // El cuerpo del correo
            
            // ADJUNTAR el PDF
            ->attachData($this->pdfData, 'Factura_DTE.pdf', [
                'mime' => 'application/pdf',
            ])
            
            // ADJUNTAR el JSON
            ->attachData($this->jsonData, 'Factura_DTE.json', [
                'mime' => 'application/json',
        ]);
    }
}
