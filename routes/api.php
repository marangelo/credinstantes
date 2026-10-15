<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('CalcularEstados', 'ApiController@CalcularEstados')->name('CalcularEstados');
Route::get('SetTelefonos', 'ApiController@SetTelefonos')->name('SetTelefonos');

// App Ruleta de Premios: máx. 10 intentos de login por minuto
Route::post('ruleta/login', 'RuletaController@Login')->middleware('throttle:10,1')->name('RuletaLogin');
Route::post('ruleta/cliente', 'RuletaController@BuscarCliente')->name('RuletaBuscarCliente');
Route::post('ruleta/preparar', 'RuletaController@Preparar')->name('RuletaPreparar');
Route::post('ruleta/girar', 'RuletaController@Girar')->name('RuletaGirar');
Route::post('ruleta/giro', 'RuletaController@Giro')->name('RuletaGiro');
Route::post('ruleta/mis-giros', 'RuletaController@MisGiros')->name('RuletaMisGiros');

