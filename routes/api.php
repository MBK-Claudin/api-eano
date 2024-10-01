<?php

use App\Http\Controllers\AnoController;
use App\Http\Controllers\AnoCtontroller;
use App\Http\Controllers\auth\authAzureController;
use App\Http\Controllers\budgetAnnuelController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\OrganisationCOntroller;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('auth/callback', [authAzureController::class, 'callback']);

// Routes Users
Route::get('users', [UserController::class, 'users'])->name('users');
Route::get('users/programme/{id}', [UserController::class, 'usersProgramme'])->name('users.programme');
Route::post('insert/contributeur/', [UserController::class, 'insertContributeurs'])->name('users.insert');

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
Route::put('edit/programme/', [ProgrammeController::class, 'editProgramme'])->name('programme.edit');

// Routes Pour les budgets annuels (PTBA)
Route::post('insert/budgetannuel/', [budgetAnnuelController::class, 'insertBudgetAnnuel'])->name('budgetannuel.insert');
Route::get('details/budgetannuel/{id}', [budgetAnnuelController::class, 'detailBudgetAnnuel']);
Route::get('budgetannuels/{id}', [budgetAnnuelController::class, 'budgetannuels']);


// Routes pour les Anos
Route::post('insert/ano/', [AnoController::class, 'insertAno']);