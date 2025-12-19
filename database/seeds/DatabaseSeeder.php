<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        //$this->call(PorcentajeSeeder::class);
        $this->call(TallerSeeder::class);
        $this->call(DepartamentoSeeder::class);
        $this->call(MunicipioSeeder::class);
    }
}
