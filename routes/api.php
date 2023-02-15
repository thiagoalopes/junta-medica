<?php

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::prefix("auth")->group(function(){

    Route::get('usuario-logado', function(){
        return Usuario::with('permissoes')->where("id", Auth::user()->id)->first();
    })->middleware('auth:api')
    ->name('usuario.info');

});

Route::prefix("administracao")->group(function(){

    Route::prefix("cargos")->group(function(){

        Route::get('', 'App\Http\Controllers\Admin\CargosController@index')
        ->name('cargos.index');

        Route::post('', 'App\Http\Controllers\Admin\CargosController@store')
        ->name('cargos.store');

        Route::get('{id}', 'App\Http\Controllers\Admin\CargosController@show')
        ->name('cargos.show')
        ->where(['id'=>'[0-9]+']);

        Route::put('{id}', 'App\Http\Controllers\Admin\CargosController@update')
        ->name('cargos.update')
        ->where(['id'=>'[0-9]+']);
    });

});
