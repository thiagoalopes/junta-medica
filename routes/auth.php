<?php

use Illuminate\Support\Facades\Route;

Route::post('/oauth/token', 'App\Http\Controllers\Auth\LoginController@issueToken');
