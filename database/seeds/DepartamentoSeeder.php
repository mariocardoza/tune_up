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
            ['codigo' => '00', 'nombre' => 'OTRO'],
            ['codigo' => '01', 'nombre' => 'AHUACHAPÁN'],
            ['codigo' => '02', 'nombre' => 'SANTA ANA'],
            ['codigo' => '03', 'nombre' => 'SONSONATE'],
            ['codigo' => '04', 'nombre' => 'CHALATENANGO'],
            ['codigo' => '05', 'nombre' => 'LA LIBERTAD'],
            ['codigo' => '06', 'nombre' => 'SAN SALVADOR'],
            ['codigo' => '07', 'nombre' => 'CUSCATLÁN'],
            ['codigo' => '08', 'nombre' => 'LA PAZ'],
            ['codigo' => '09', 'nombre' => 'CABAÑAS'],
            ['codigo' => '10', 'nombre' => 'SAN VICENTE'],
            ['codigo' => '11', 'nombre' => 'USULUTÁN'],
            ['codigo' => '12', 'nombre' => 'SAN MIGUEL'],
            ['codigo' => '13', 'nombre' => 'MORAZÁN'],
            ['codigo' => '14', 'nombre' => 'LA UNIÓN'],
        ];

        foreach ($departamentos as $depto) {
            DB::table('departamentos')->updateOrInsert(
                ['codigo' => $depto['codigo']],
                ['nombre' => $depto['nombre']]
            );
        }
    }
}
