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
Route::post('/oauth/token', 'App\Http\Controllers\Auth\LoginController@issueToken');

Route::prefix("auth")->group(function(){


    Route::prefix("usuarios")->group(function(){

        Route::get('logado', 'App\Http\Controllers\Admin\UsuariosController@logged')
        ->name('usuario.info');

        Route::get('permissoes', 'App\Http\Controllers\Admin\UsuariosController@permissoes')
        ->name('usuario.permissoes');

        Route::get('', 'App\Http\Controllers\Admin\UsuariosController@index')
        ->name('usuarios.index');

        Route::post('', 'App\Http\Controllers\Admin\UsuariosController@store')
        ->name('usuarios.store');

        Route::get('{id}', 'App\Http\Controllers\Admin\UsuariosController@show')
        ->name('usuarios.show')
        ->where(['id'=>'[0-9]+']);

        Route::put('{id}', 'App\Http\Controllers\Admin\UsuariosController@update')
        ->name('usuarios.update')
        ->where(['id'=>'[0-9]+']);

        Route::put('{id}/permissoes', 'App\Http\Controllers\Admin\UsuariosController@updatePermissoesUsuario')
        ->name('usuarios.permissoes.update')
        ->where(['id'=>'[0-9]+']);

        Route::put('{id}/disable', 'App\Http\Controllers\Admin\UsuariosController@disable')
        ->name('usuarios.disable')
        ->where(['id'=>'[0-9]+']);

        Route::put('{id}/enable', 'App\Http\Controllers\Admin\UsuariosController@enable')
        ->name('usuarios.enable')
        ->where(['id'=>'[0-9]+']);
    });

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
