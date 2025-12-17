<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDtesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dtes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cotizacion_id')->unsigned();
            $table->integer('correlativo');
            $table->integer('anio');
            $table->string('tipoDte');
            $table->string('codigoGeneracion');
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
        Schema::dropIfExists('dtes');
    }
}
