<?php

use Illuminate\Database\Seeder;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            '00' => ['00'=>'OTRO'],
            '01' => ['13'=>'AHUACHAPÁN NORTE','14'=>'AHUACHAPÁN CENTRO','15'=>'AHUACHAPÁN SUR'],
            '02' => ['14'=>'SANTA ANA NORTE','15'=>'SANTA ANA CENTRO','16' => 'SANTA ANA ESTE','17' => 'SANTA ANA OESTE'],
            '03' => ['17'=>'SONSONATE NORTE','18'=>'SONSONANTE CENTRO','19'=>'SONSONATE ESTE','20'=>'SONSONATE OESTE'],
            '04' => ['34'=>'CHALATENANGO NORTE','35'=>'CHALATENANGO CENTRO','36'=>'CHALATENANGO SUR'],
            '05' => ['23'=>'LA LIBERTAD NORTE','24'=>'LA LIBERTAD CENTRO','25'=>'LA LIBERTAD OESTE','26' => 'LA LIBERTAD ESTE', '27' => 'LA LIBERTAD COSTA','28'=>'LA LIBERTAD SUR'],
            '06' => ['20'=>'SAN SALVADOR NORTE','21'=>'SAN SALVADOR OESTE','22'=>'SAN SALVADOR ESTE','23' => 'SAN SALVADOR CENTRO', '24' => 'SAN SALVADOR SUR'],
            '07' => ['17'=>'CUSCATLAN NORTE','18'=>'CUSCATLAN SUR'],
            '08' => ['23'=>'LA PAZ OESTE','24'=>'LA PAZ CENTRO','25' => 'LA PAZ ESTE'],
            '09' => ['10'=>'CABAÑAS OESTE','11'=>'CABAÑAS ESTE'],
            '10' => ['14'=>'SAN VICENTE NORTE','15'=>'SAN VICENTE SUR'],
            '11' => ['24'=>'USULUTAN NORTE','25'=>'USULUTAN ESTE','26' => 'USULUTAN OESTE'],
            '12' => ['21'=>'SAN MIGUEL NORTE','22'=>'SAN MIGUEL CENTRO','23' => 'SAN MIGUEL OESTE'],
            '13' => ['27'=>'MORAZÁN NORTE','28'=>'MORAZÁN SUR'],
            '14' => ['19'=>'LA UNIÓN NORTE','20'=>'LA UNIÓN SUR']
        ];

        foreach ($data as $depto_cod => $municipios) {
            foreach ($municipios as $muni_cod => $nombre) {
                $codigo_completo = $depto_cod . $muni_cod;
                DB::table('municipios')->updateOrInsert(
                    ['codigo' => $codigo_completo],
                    [
                        'departamento_codigo' => $depto_cod,
                        'nombre' => $nombre
                    ]
                );
            }
        }
    }
}
