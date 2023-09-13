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

Route::get('Dashboard', 'CredinstanteController@getDashboard')->name('Dashboard');
Route::get('Clientes', 'CredinstanteController@getClientes')->name('Clientes');
Route::get('Usuarios', 'CredinstanteController@getUsuarios')->name('Usuarios');

Route::get('Reporte', 'CredinstanteController@getReporte')->name('Reporte');

Route::get('Municipios', 'CredinstanteController@getMunicipios')->name('Municipios');
Route::get('Departamento', 'CredinstanteController@getDepartamento')->name('Departamento');
Route::get('DiasSemna', 'CredinstanteController@getDiasSemna')->name('DiasSemna');

Route::get('Perfil/{id}', 'CredinstanteController@getPerfil')->name('Perfil/{id}');

Route::get('voucher', 'CredinstanteController@prtVoucher')->name('voucher');
Route::post('SaveNewCredito', 'CredinstanteController@SaveNewCredito')->name('SaveNewCredito');


Route::post('SaveNewMunicipio', 'CredinstanteController@SaveNewMunicipio')->name('SaveNewMunicipio');
Route::get('rmMunicipio/{id}', 'CredinstanteController@rmMunicipio')->name('rmMunicipio/{id}');

Route::post('SaveNewDepartamento', 'CredinstanteController@SaveNewDepartamento')->name('SaveNewDepartamento');
Route::get('rmDepartamento/{id}', 'CredinstanteController@rmDepartamento')->name('rmDepartamento/{id}');

Route::post('AddDiaSemana', 'CredinstanteController@AddDiaSemana')->name('AddDiaSemana');
Route::get('rmDiaSemana/{id}', 'CredinstanteController@rmDiaSemana')->name('rmDiaSemana/{id}');

Route::post('SaveNewAbono', 'CredinstanteController@SaveNewAbono')->name('SaveNewAbono');
Route::get('getHistoricoAbono/{ID}', 'CredinstanteController@getHistoricoAbono')->name('getHistoricoAbono');

Route::post('AddCredito', 'CredinstanteController@AddCredito')->name('AddCredito');


Route::post('rmElem', 'CredinstanteController@Remover')->name('rmElem');
