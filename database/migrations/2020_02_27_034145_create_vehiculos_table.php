<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiculosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cliente_id')->unsigned();
            $table->bigInteger('marca_id')->unsigned();
            $table->bigInteger('modelo_id')->unsigned()->nullable();
            $table->bigInteger('color')->nullable();
            $table->integer('anio')->nullable();
            $table->string('placa');
            $table->string('motor');
            $table->string('chasis')->nullable();
            $table->string('vin')->nullable();
            $table->string('kilometraje')->nullable();
            $table->string('km_proxima')->nullable();
            $table->text('notas')->nullable();
            $table->string('tipo')->nullable();
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
        Schema::dropIfExists('vehiculos');
    }
}
