<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return view('auth.login');
});

Route::get('cotizaciones/email/{id}','CotizacionController@email');
Route::post('cotizaciones/enviar','CotizacionController@enviar');
Route::post('cotizaciones/updateDate','CotizacionController@updateDate');

Auth::routes();
Route::post('authenticate','Auth\LoginController@authenticate')->name('authenticate');

///////////  RUTAS DE RESPALDO Y RESTAURAR BASE DE DATOS
Route::get('backups','BackupController@index')->name('backups.index');
Route::get('backups/create','BackupController@create')->name('backup.create');
Route::get('backups/descargar/{file_name}','BackupController@descargar');
Route::get('backups/eliminar/{file_name}', 'BackupController@eliminar');
Route::get('backups/restaurar/{file_name}', 'BackupController@restaurar');

Route::get('/home', 'HomeController@index')->name('home');
Route::post('administracion/porcentajes','AdministracionController@porcentaje');
Route::Resource('administracion','AdministracionController');

Route::get('clientes/obtenermodelos/{id}','ClienteController@obtenerModelos');
Route::Resource('clientes','ClienteController');
Route::get('ivaporventas','CotizacionController@ivaventas');

Route::post('cotizaciones/cambiarkm','CotizacionController@cambiarkm');
Route::post('cotizaciones/eliva/{id}','CotizacionController@el_via');
Route::get('cotizaciones/vehiculos/{id}','CotizacionController@obtenervehiculos');
Route::get('cotizaciones/previas/{id}','CotizacionController@obtenerprevia');
Route::get('cotizaciones/guardadas/{id}','CotizacionController@obtenerguardadas');
Route::get('cotizaciones/pdfcotizacion/{id}','CotizacionController@pdf');
Route::post('cotizaciones/convertir','CotizacionController@convertir');

Route::Resource('cotizaciones','CotizacionController');
Route::get('exportaciones/reporte/{id}','ExportacionController@reporte');
Route::Resource('exportaciones','ExportacionController');
Route::get('facturas/reporte/{id}','FacturaController@reporte');
Route::Resource('facturas','FacturaController');
Route::get('creditos/reporte/{id}','CreditoController@reporte');
Route::post('creditos/clonar','CreditoController@clonar');
Route::post('creditos/facturar_a','CreditoController@facturar_a');
Route::post('creditos/cancelarfacturar','CreditoController@cancelarfacturar');
Route::post('creditos/imprimir_veh','CreditoController@imprimir_veh');
Route::Resource('creditos','CreditoController');


Route::Resource('marcas','MarcaController');
Route::Resource('modelos','ModeloController');
Route::get('vehiculos/info/{id}','VehiculoController@info');
Route::get('vehiculos/porplaca','VehiculoController@placa');

Route::get('vehiculos/historial/{placa}','VehiculoController@historial');

Route::Resource('vehiculos','VehiculoController');
Route::post('repuestos/guardar','RepuestoController@guardar');
Route::post('repuestos/guardar2','RepuestoController@guardar2');
Route::Resource('repuestos','RepuestoController');
Route::post('trabajos/guardar','TrabajoController@guardar');
Route::post('trabajos/guardar2','TrabajoController@guardar2');
Route::Resource('trabajos','TrabajoController');
Route::get('trabajodetalles/{id}/edit2','TrabajodetallesController@edit2');
Route::post('trabajodetalles/guardar','TrabajodetallesController@guardar');
Route::put('trabajodetalles2/{id}','TrabajodetallesController@update2');
Route::delete('trabajodetalles/destroy2/{id}','TrabajodetallesController@destroy2');
Route::Resource('trabajodetalles','TrabajodetallesController');
Route::get('repuestodetalles/{id}/edit2','RepuestodetallesController@edit2');
Route::put('repuestodetalles2/{id}','RepuestodetallesController@update2');
Route::delete('repuestodetalles/destroy2/{id}','RepuestodetallesController@destroy2');
Route::post('repuestodetalles/guardar','RepuestodetallesController@guardar');
Route::Resource('repuestodetalles','RepuestodetallesController');

Route::get('busqueda','HomeController@buscar');

Route::post('facturacion','FacturacionController@generarFactura');
Route::post('facturacion/anular','FacturacionController@InvalidarDTE');

Route::get('/phpinfo', function () {
    return phpinfo();
});