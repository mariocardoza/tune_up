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
            '01' => ['01'=>'Ahuachapán','02'=>'Jujutla','03'=>'Atiquizaya','04'=>'Concepción de Ataco','05'=>'El Refugio','06'=>'Guaymango','07'=>'Apaneca','08'=>'San Francisco Menéndez','09'=>'San Lorenzo','10'=>'San Pedro Puxtla','11'=>'Turín','12'=>'Tacuba'],
            '02' => ['01'=>'Candelaria de la Frontera','02'=>'Coatepeque','03'=>'Chalchuapa','04'=>'El Congo','05'=>'El Porvenir','06'=>'Masahuat','07'=>'Metapán','08'=>'San Antonio Pajonal','09'=>'San Sebastián Salitrillo','10'=>'Santa Ana','11'=>'Santa Rosa Guachipilín','12'=>'Santiago de la Frontera','13'=>'Texistepeque'],
            '03' => ['01'=>'Sonsonate','02'=>'Acajutla','03'=>'Armenia','04'=>'Caluco','05'=>'Cuisnahuat','06'=>'Izalco','07'=>'Juayúa','08'=>'Nahuizalco','09'=>'Nahulingo','10'=>'Salcoatitán','11'=>'San Antonio del Monte','12'=>'San Julián','13'=>'Santa Catarina Masahuat','14'=>'Santa Isabel Ishuatán','15'=>'Santo Domingo de Guzmán','16'=>'Sonzacate'],
            '04' => ['01'=>'Chalatenango','02'=>'Arcatao','03'=>'Azacualpa','04'=>'Citalá','05'=>'Comalapa','06'=>'Concepción Quezaltepeque','07'=>'Dulce Nombre de María','08'=>'El Carrizal','09'=>'El Paraíso','10'=>'La Laguna','11'=>'La Palma','12'=>'La Reina','13'=>'Las Vueltas','14'=>'Nombre de Jesús','15'=>'Nueva Concepción','16'=>'Nueva Trinidad','17'=>'Ojos de Agua','18'=>'Potonico','19'=>'San Antonio de la Cruz','20'=>'San Antonio Los Ranchos','21'=>'San Fernando','22'=>'San Francisco Lempa','23'=>'San Francisco Morazán','24'=>'San Ignacio','25'=>'San Isidro Labrador','26'=>'San José Cancasque','27'=>'San José Las Flores','28'=>'San Luis del Carmen','29'=>'San Miguel de Mercedes','30'=>'San Rafael','31'=>'Santa Rita','32'=>'Tejutla','33'=>'San Juan Guarita'],
            '05' => ['01'=>'Antiguo Cuscatlán','02'=>'Chiltiupán','03'=>'Ciudad Arce','04'=>'Colón','05'=>'Comasagua','06'=>'Huizúcar','07'=>'Jicalapa','08'=>'La Libertad','09'=>'Nuevo Cuscatlán','10'=>'Santa Tecla','11'=>'Quezaltepeque','12'=>'Sacacoyo','13'=>'San José Villanueva','14'=>'San Juan Opico','15'=>'San Matías','16'=>'San Pablo Tacachico','17'=>'Tamanique','18'=>'Talnique','19'=>'Teotepeque','20'=>'Tepecoyo','21'=>'Zaragoza','22'=>'San José Villanueva'],
            '06' => ['01'=>'Aguilares','02'=>'Apopa','03'=>'Ayutuxtepeque','04'=>'Cuscatancingo','05'=>'Delgado','06'=>'El Paisnal','07'=>'Guazapa','08'=>'Ilopango','09'=>'Mejicanos','10'=>'Nejapa','11'=>'Panchimalco','12'=>'Rosario de Mora','13'=>'San Marcos','14'=>'San Martín','15'=>'San Salvador','16'=>'Santiago Texacuangos','17'=>'Santo Tomás','18'=>'Soyapango','19'=>'Tonacatepeque'],
            '07' => ['01'=>'Cojutepeque','02'=>'Candelaria','03'=>'El Carmen','04'=>'El Rosario','05'=>'Monte San Juan','06'=>'Oratorio de Concepción','07'=>'San Bartolomé Perulapía','08'=>'San Cristóbal','09'=>'San José Guayabal','10'=>'San Pedro Perulapán','11'=>'San Rafael Cedros','12'=>'San Ramón','13'=>'Santa Cruz Analquito','14'=>'Santa Cruz Michapa','15'=>'Suchitoto','16'=>'Tenancingo'],
            '08' => ['01'=>'Zacatecoluca','02'=>'Cuyultitán','03'=>'El Rosario','04'=>'Jerusalén','05'=>'Mercedes La Ceiba','06'=>'Olocuilta','07'=>'Paraíso de Osorio','08'=>'San Antonio Masahuat','09'=>'San Emigdio','10'=>'San Francisco Chinameca','11'=>'San Juan Nonualco','12'=>'San Juan Talpa','13'=>'San Juan Tepezontes','14'=>'San Luis La Herradura','15'=>'San Luis Talpa','16'=>'San Pedro Masahuat','17'=>'San Pedro Nonualco','18'=>'San Rafael Obrajuelo','19'=>'Santa María Ostuma','20'=>'Santiago Nonualco','21'=>'Tapalhuaca','22'=>'Cuyultitán'],
            '09' => ['01'=>'Sensuntepeque','02'=>'Cinquera','03'=>'Dolores','04'=>'Guacotecti','05'=>'Ilobasco','06'=>'Jutiapa','07'=>'San Isidro','08'=>'Tejutepeque','09'=>'Victoria'],
            '10' => ['01'=>'San Vicente','02'=>'Apastepeque','03'=>'Guadalupe','04'=>'San Cayetano Istepeque','05'=>'Santa Clara','06'=>'Santo Domingo','07'=>'San Esteban Catarina','08'=>'San Ildefonso','09'=>'San Lorenzo','10'=>'San Sebastián','11'=>'Tepetitán','12'=>'Verapaz','13'=>'Tecoluca'],
            '11' => ['01'=>'Usulután','02'=>'Alegría','03'=>'Berlín','04'=>'California','05'=>'Concepción Batres','06'=>'El Triunfo','07'=>'Ereguayquín','08'=>'Estanzuelas','09'=>'Jiquilisco','10'=>'Jucuapa','11'=>'Jucuarán','12'=>'Mercedes Umaña','13'=>'Nueva Granada','14'=>'Ozatlán','15'=>'Puerto El Triunfo','16'=>'San Agustín','17'=>'San Buenaventura','18'=>'San Dionisio','19'=>'San Francisco Javier','20'=>'Santa Elena','21'=>'Santa María','22'=>'Santiago de María','23'=>'Tecapán'],
            '12' => ['01'=>'San Miguel','02'=>'Carolina','03'=>'Ciudad Barrios','04'=>'Comacarán','05'=>'Chapeltique','06'=>'Chinameca','07'=>'Chirilagua','08'=>'El Tránsito','09'=>'Lolotique','10'=>'Moncagua','11'=>'Nueva Guadalupe','12'=>'Nuevo Edén de San Juan','13'=>'Quelepa','14'=>'San Antonio','15'=>'San Gerardo','16'=>'San Jorge','17'=>'San Luis de la Reina','18'=>'San Rafael Oriente','19'=>'Sesori','20'=>'Uluazapa'],
            '13' => ['01'=>'San Francisco Gotera','02'=>'Arambala','03'=>'Cacaopera','04'=>'Corinto','05'=>'Chilanga','06'=>'Delicias de Concepción','07'=>'El Divisadero','08'=>'El Rosario','09'=>'Gualococti','10'=>'Guatajiagua','11'=>'Joateca','12'=>'Jocoaitique','13'=>'Jocoro','14'=>'Lolotiquillo','15'=>'Meanguera','16'=>'Osicala','17'=>'Perquín','18'=>'San Carlos','19'=>'San Fernando','20'=>'San Isidro','21'=>'San Simón','22'=>'Sensembra','23'=>'Sociedad','24'=>'Torola','25'=>'Yamabal','26'=>'Yoloaiquín'],
            '14' => ['01'=>'La Unión','02'=>'Anamorós','03'=>'Bolívar','04'=>'Concepción de Oriente','05'=>'Conchagua','06'=>'El Carmen','07'=>'El Sauce','08'=>'Intipucá','09'=>'Lislique','10'=>'Meanguera del Golfo','11'=>'Nueva Esparta','12'=>'Pasaquina','13'=>'Polorós','14'=>'San Alejo','15'=>'San José','16'=>'Santa Rosa de Lima','17'=>'Yayantique','18'=>'Yucuaiquín']
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
