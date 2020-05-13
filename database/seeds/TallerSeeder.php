<?php

use Illuminate\Database\Seeder;
use App\Taller;
use App\User;

class TallerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            //'tallers',
        ]);

        $taller= new Taller();
	    $taller->propietario='Hector Rivas';
	    $taller->direccion='CALLE SAN CARLOS 1004, FINAL 17 AV. NORTE, SAN SALVADOR';
	    $taller->telefono1='2225-4438';
	    $taller->telefono2='2317-3535';
	    $taller->celular='7730-3565';
	    $taller->email='h_rivas47@yahoo.com';
	    $taller->save();

        $user=new User();
        $user->username='administrador';
        $user->name='Héctor Rivas';
        $user->email='h_rivas47@yahoo.com';
        $user->password=bcrypt('administrador');
        $user->save();
    }

    public function truncateTables(array $tables)
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        //DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
