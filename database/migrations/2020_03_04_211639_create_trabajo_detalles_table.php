<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrabajoDetallesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trabajo_detalles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cotizacion_id')->unsigned();
            $table->bigInteger('trabajo_id')->unsigned();
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
        Schema::dropIfExists('trabajo_detalles');
    }
}
