<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $departamentos = [
            ['codigo' => '01', 'nombre' => 'Ahuachapán'],
            ['codigo' => '02', 'nombre' => 'Santa Ana'],
            ['codigo' => '03', 'nombre' => 'Sonsonate'],
            ['codigo' => '04', 'nombre' => 'Chalatenango'],
            ['codigo' => '05', 'nombre' => 'La Libertad'],
            ['codigo' => '06', 'nombre' => 'San Salvador'],
            ['codigo' => '07', 'nombre' => 'Cuscatlán'],
            ['codigo' => '08', 'nombre' => 'La Paz'],
            ['codigo' => '09', 'nombre' => 'Cabañas'],
            ['codigo' => '10', 'nombre' => 'San Vicente'],
            ['codigo' => '11', 'nombre' => 'Usulután'],
            ['codigo' => '12', 'nombre' => 'San Miguel'],
            ['codigo' => '13', 'nombre' => 'Morazán'],
            ['codigo' => '14', 'nombre' => 'La Unión'],
        ];

        foreach ($departamentos as $depto) {
            DB::table('departamentos')->updateOrInsert(
                ['codigo' => $depto['codigo']],
                ['nombre' => $depto['nombre']]
            );
        }
    }
}
