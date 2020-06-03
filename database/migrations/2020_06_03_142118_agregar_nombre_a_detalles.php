<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarNombreADetalles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trabajo_detalles', function (Blueprint $table) {
            $table->string('nombre')->nullable();
        });

        Schema::table('repuesto_detalles', function (Blueprint $table) {
            $table->string('nombre')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trabajo_detalles', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });

        Schema::table('repuesto_detalles', function (Blueprint $table) {
            $table->dropColumn('nombre');
        });
    }
}
