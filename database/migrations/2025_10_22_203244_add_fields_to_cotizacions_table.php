<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToCotizacionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->string("numero_control")->nullable();
            $table->string("codigo_generacion")->nullable();
            $table->string("sello_generacion")->nullable();
            $table->datetime("fecha_generacion")->nullable();
            $table->string("fecha_procesamiento")->nullable();
            $table->string("tipo_dte")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cotizaciones', function (Blueprint $table) {
            $table->dropColumn("numero_control");
            $table->dropColumn("codigo_generacion");
            $table->dropColumn("sello_generacion");
            $table->dropColumn("fecha_generacion");
            $table->dropColumn("fecha_procesamiento");
            $table->dropColumn("tipo_dte");
        });
    }
}
