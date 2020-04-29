<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepuestoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repuesto_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cotizacion_id')->unsigned();
            $table->bigInteger('repuesto_id')->unsigned();
            $table->double('precio',8,2);
            $table->integer('cantidad');
            $table->integer('estado')->default(1);
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
        Schema::dropIfExists('repuesto_detalles');
    }
}
