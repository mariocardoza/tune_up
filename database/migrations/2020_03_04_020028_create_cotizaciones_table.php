<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('vehiculo_id')->unsigned();
            $table->bigInteger('cliente_id')->unsigned();
            $table->integer('tipo_documento');
            $table->date('fecha');
            $table->integer('estado')->default(1);
            $table->double('subtotal',8,2);
            $table->double('iva',8,2)->nullable();
            $table->double('total',8,2)->nullable();
            $table->double('iva_r',8,2)->nullable();
            $table->string('clasificacion')->nullable();
            $table->integer('n_impresiones')->default(0);
            $table->string('coniva')->nullable();
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
        Schema::dropIfExists('cotizaciones');
    }
}
