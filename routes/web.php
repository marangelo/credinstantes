<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

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

Route::get('/', 'Auth\LoginController@showLoginForm');
//Route::get('/', 'CredinstanteController@getDashboard');
Route::get('/logout', 'Auth\LoginController@logout');
Route::post('updatePassword', 'CredinstanteController@updatePassword')->name('updatePassword');;

Route::get('Dashboard', 'CredinstanteController@getDashboard')->name('Dashboard');

Route::get('Activos', 'CredinstanteController@getClientes')->name('Activos');
Route::get('Inactivos', 'CredinstanteController@getInactivos')->name('Inactivos');

Route::get('Usuarios', 'CredinstanteController@getUsuarios')->name('Usuarios');

Route::get('Municipios', 'CredinstanteController@getMunicipios')->name('Municipios');
Route::get('Departamento', 'CredinstanteController@getDepartamento')->name('Departamento');
Route::get('DiasSemna', 'CredinstanteController@getDiasSemna')->name('DiasSemna');

Route::get('Perfil/{id}', 'CredinstanteController@getPerfil')->name('Perfil/{id}');

Route::get('voucher/{id}', 'CredinstanteController@prtVoucher')->name('voucher/{id}');
Route::get('voucherParcial/{id}', 'CredinstanteController@prtVoucherParcial')->name('voucherParcial/{id}');

Route::post('SaveNewCredito', 'CredinstanteController@SaveNewCredito')->name('SaveNewCredito');
Route::post('editClient', 'CredinstanteController@editClient')->name('editClient');


Route::post('SaveNewMunicipio', 'CredinstanteController@SaveNewMunicipio')->name('SaveNewMunicipio');
Route::get('rmMunicipio/{id}', 'CredinstanteController@rmMunicipio')->name('rmMunicipio/{id}');

Route::post('SaveNewDepartamento', 'CredinstanteController@SaveNewDepartamento')->name('SaveNewDepartamento');
Route::get('rmDepartamento/{id}', 'CredinstanteController@rmDepartamento')->name('rmDepartamento/{id}');


Route::get('Zonas', 'CredinstanteController@getZona')->name('Zonas');
Route::post('AddZona', 'CredinstanteController@addZona')->name('AddZona');
Route::get('rmZona/{id}', 'CredinstanteController@rmZona')->name('rmZona/{id}');


Route::post('AddDiaSemana', 'CredinstanteController@AddDiaSemana')->name('AddDiaSemana');
Route::get('rmDiaSemana/{id}', 'CredinstanteController@rmDiaSemana')->name('rmDiaSemana/{id}');

Route::post('SaveNewAbono', 'CredinstanteController@SaveNewAbono')->name('SaveNewAbono');
Route::get('getHistoricoAbono/{ID}', 'CredinstanteController@getHistoricoAbono')->name('getHistoricoAbono');

Route::post('AddCredito', 'CredinstanteController@AddCredito')->name('AddCredito');

//RUTAS PARA REMOVER
Route::post('rmElem', 'CredinstanteController@Remover')->name('rmElem');
Route::post('rmAbono', 'CredinstanteController@rmAbono')->name('rmAbono');
Route::post('LockUser', 'CredinstanteController@LockUser')->name('LockUser');

//RUTAS DE REPORTES
Route::get('Visitar', 'ReportsController@Visitar')->name('Visitar');
Route::get('Abonos', 'ReportsController@Abonos')->name('Abonos');
Route::get('Morosidad', 'ReportsController@Morosidad')->name('Morosidad');

Route::post('getVisitar', 'ReportsController@getVisitar')->name('getVisitar');
Route::post('getAbonos', 'ReportsController@getAbonos')->name('getAbonos');
Route::post('getMorosidad', 'ReportsController@getMorosidad')->name('getMorosidad');
Route::get('getSaldoAbono/{ID}/{OP}', 'CredinstanteController@getSaldoAbono')->name('getSaldoAbono');
Route::get('getDashboard/{ID}', 'ReportsController@getDashboard')->name('getDashboard');

Route::post('creditCheck', 'CredinstanteController@creditCheck')->name('creditCheck');
Route::post('exportAbonos', 'ReportsController@exportAbonos')->name('exportAbonos');
Route::post('exportVisita', 'ReportsController@exportVisita')->name('exportVisita');
Route::post('getAllCredit', 'CredinstanteController@getAllCredit')->name('getAllCredit');
Route::post('ChanceStatus', 'CredinstanteController@ChanceStatus')->name('ChanceStatus');

Route::get('MultiAbono', 'CredinstanteController@MultiAbonos')->name('MultiAbono');
Route::get('Bluid', 'CredinstanteController@Bluid')->name('Bluid');


Route::post('AddNewUser', 'CredinstanteController@AddNewUser')->name('AddNewUser');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
Route::get('CalcularEstados', 'ApiController@CalcularEstados')->name('CalcularEstados');

Route::get('Promotor', 'CredinstanteController@Promotor')->name('Promotor');
Route::get('getDashboardPromotor/{ID}', 'ReportsController@getDashboardPromotor')->name('getDashboardPromotor');
Route::get('Desembolsados', 'CredinstanteController@Desembolsados')->name('Desembolsados');
Route::get('getClientesDesembolsados', 'ReportsController@getClientesDesembolsados')->name('getClientesDesembolsados');
Route::get('MetricasPromotor', 'CredinstanteController@MetricasPromotor')->name('MetricasPromotor');
Route::get('getMetricasPromotor/{ID}', 'ReportsController@getMetricasPromotor')->name('getMetricasPromotor');
Route::get('ExportMetricasPromotor', 'ReportsController@ExportMetricasPromotor')->name('ExportMetricasPromotor');


