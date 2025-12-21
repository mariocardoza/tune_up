<?php

use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csvFile = fopen(base_path("database/data/paises.csv"), "r");
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            if($data[0] != ''){
                DB::table('pais')->updateOrInsert(
                ['codigo' => $data[0]], 
                ['nombre' => $data[1]] 
                );
            }
            
        }
        fclose($csvFile);
    }
}
