<?php

use Illuminate\Database\Seeder;
use App\Porcentaje;

class PorcentajeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            //'porcentajes',
        ]);

	    $por= new Porcentaje();
	    $por->nombre='Renta';
	    $por->nombre_simple='renta';
	    $por->porcentaje=10;
	    $por->save();

	    $por= new Porcentaje();
	    $por->nombre='IVA';
	    $por->nombre_simple='iva';
	    $por->porcentaje=13;
	    $por->save();

	    $por= new Porcentaje();
	    $por->nombre='IVA Retenido';
	    $por->nombre_simple='ivar';
	    $por->porcentaje=1;
	    $por->save();
    }

    public function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
