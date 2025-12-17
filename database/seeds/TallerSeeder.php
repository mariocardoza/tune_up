<?php

use Illuminate\Database\Seeder;
use App\Taller;
use App\User;
use App\Documento;

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
            'tallers',
            'documentos',
            //'users',
        ]);

        $taller= new Taller();
	    $taller->propietario='Hector Rivas';
	    $taller->direccion='CALLE SAN CARLOS 1004, FINAL 17 AV. NORTE, COL LAYCO, SAN SALVADOR';
	    $taller->telefono1='2225-4438';
	    $taller->telefono2='2562-7454';
	    $taller->celular='7730-3565';
	    $taller->email='h_rivas47@yahoo.com';
	    $taller->nrc='131438-2';
	    $taller->nit='0614-040447-002-8';
	    $taller->actividad_economica='Reparación mecánica de vehículos automotores';
	    $taller->nombre='TUNEUP SERVICE';
	    $taller->save();

        $dui = new Documento();
        $dui->tipo_documento = "13";
        $dui->nombre_documento = "DUI";
        $dui->save();

        $nit = new Documento();
        $nit->tipo_documento = "36";
        $nit->nombre_documento = "NIT";
        $nit->save();

        $pasaporte = new Documento();
        $pasaporte->tipo_documento = "03";
        $pasaporte->nombre_documento = "Pasaporte";
        $pasaporte->save();

        $carnet = new Documento();
        $carnet->tipo_documento = "02";
        $carnet->nombre_documento = "Carnet de Residente";
        $carnet->save();

        $otro = new Documento();
        $otro->tipo_documento = "37";
        $otro->nombre_documento = "Otro";
        $otro->save();

        /*$user=new User();
        $user->username='tuneupservice';
        $user->name='Héctor Rivas';
        $user->email='h_rivas47@yahoo.com';
        $user->password=bcrypt('donmemitoA1');
        $user->save();

        $user=new User();
        $user->username='marito';
        $user->name='Mario Cardoza';
        $user->email='mario.cardoza.huezo@gmail.com';
        $user->password=bcrypt('mar1ocard0za');
        $user->save();*/
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
