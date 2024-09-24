<?php

use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\OrganisationCOntroller;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Routes Users
Route::get('users', [UserController::class, 'users']);

// Routes Organisations
Route::get('organisations', [OrganisationCOntroller::class, 'organisations'])->name('organisations.all');

// Routes Pour les Objectifs stratégiques
Route::post('insert/objectif/', [ObjectifController::class, 'insertObjectif'])->name('objectif.insert');
Route::get('objectifs', [ObjectifController::class, 'objectifs'])->name('objectif');
Route::get('select/objectif/{id}', [ObjectifController::class, 'selectObjectif'])->name('objectif.select');
Route::put('edit/objectif/', [ObjectifController::class, 'editObjectif'])->name('objectif.edit');
Route::delete('delete/objectif/{id}', [ObjectifController::class, 'deleteObjectif'])->name('objectif.delete');

// Routes Pour les programmes
Route::get('programmes', [ProgrammeController::class, 'programmes'])->name('programmes');
Route::post('insert/programme/', [ProgrammeController::class, 'insertProgramme'])->name('programme.insert');
Route::get('select/programme/{id}', [ProgrammeController::class, 'selectProgramme'])->name('programme.select');
Route::get('contributeurs', [UserController::class, 'contributeurs'])->name('programme.contributeurs');