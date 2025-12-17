<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatosToTallersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tallers', function (Blueprint $table) {
            $table->string('nrc')->nullable();
            $table->string('nit')->nullable();
            $table->string('actividad_economica')->nullable();
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
        Schema::table('tallers', function (Blueprint $table) {
            $table->dropColumn('nrc');
            $table->dropColumn('nit');
            $table->dropColumn('actividad_economica');
            $table->dropColumn('nombre');
        });
    }
}
