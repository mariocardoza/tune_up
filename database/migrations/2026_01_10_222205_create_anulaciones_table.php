<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnulacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anulaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cotizacion_id');
            $table->string('estado');
            $table->string("codigo_generacion");
            $table->string("sello_recibido");
            $table->string("fhProcesamiento");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anulaciones');
    }
}
