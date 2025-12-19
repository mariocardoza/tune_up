<?php

use Illuminate\Database\Seeder;

class ActividadEconomicaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/data/actividades.csv"), "r");
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if($data[0] != ''){
                $codigoLimpiado = str_pad(trim($data[0]), 5, "0", STR_PAD_LEFT);
                DB::table('actividad_economicas')->updateOrInsert(
                ['codigo' => $codigoLimpiado], 
                ['nombre' => $data[1]] 
                );
            }
            
        }
        fclose($csvFile);
    }
}
