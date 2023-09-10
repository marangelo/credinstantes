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
//Route::get('/', 'HomeController@getDashboard');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('Dashboard', 'HomeController@getDashboard')->name('Dashboard');
Route::get('Clientes', 'HomeController@getClientes')->name('Clientes');
Route::get('Usuarios', 'HomeController@getUsuarios')->name('Usuarios');

Route::get('Reporte', 'HomeController@getReporte')->name('Reporte');

Route::get('Municipios', 'HomeController@getMunicipios')->name('Municipios');
Route::get('Departamento', 'HomeController@getDepartamento')->name('Departamento');
Route::get('DiasSemna', 'HomeController@getDiasSemna')->name('DiasSemna');

Route::get('Perfil/{id}', 'HomeController@getPerfil')->name('Perfil/{id}');

Route::get('voucher', 'HomeController@prtVoucher')->name('voucher');
Route::post('SaveNewCredito', 'HomeController@SaveNewCredito')->name('SaveNewCredito');


Route::post('SaveNewMunicipio', 'HomeController@SaveNewMunicipio')->name('SaveNewMunicipio');
Route::get('rmMunicipio/{id}', 'HomeController@rmMunicipio')->name('rmMunicipio/{id}');

Route::post('SaveNewDepartamento', 'HomeController@SaveNewDepartamento')->name('SaveNewDepartamento');
Route::get('rmDepartamento/{id}', 'HomeController@rmDepartamento')->name('rmDepartamento/{id}');

Route::post('AddDiaSemana', 'HomeController@AddDiaSemana')->name('AddDiaSemana');
Route::get('rmDiaSemana/{id}', 'HomeController@rmDiaSemana')->name('rmDiaSemana/{id}');
