<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();

#Route::get('/', 'Auth\LoginController@showLoginForm');
Route::get('/', 'HomeController@getDashboard');
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('Dashboard', 'HomeController@getDashboard')->name('Dashboard');
Route::get('Clientes', 'HomeController@getClientes')->name('Clientes');
Route::get('Usuarios', 'HomeController@getUsuarios')->name('Usuarios');

Route::get('Municipios', 'HomeController@getMunicipios')->name('Municipios');
Route::get('Departamento', 'HomeController@getDepartamento')->name('Departamento');
Route::get('DiasSemna', 'HomeController@getDiasSemna')->name('DiasSemna');

Route::get('Perfil/{id}', 'HomeController@getPerfil')->name('Perfil/{id}');
