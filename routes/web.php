<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\authAzureController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('auth', [authAzureController::class, 'login']);
