<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposDteToClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string("tipo_documento")->nullable();
            $table->string("numero_documento")->nullable();
            $table->string("codActividad")->nullable();
            $table->string("descActividad")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('tipo_documento');
            $table->dropColumn('codActividad');
            $table->dropColumn('descActividad');
            $table->dropColumn('numero_documento');
        });
    }
}
