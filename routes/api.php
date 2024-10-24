<?php

use App\Http\Controllers\ActiviteController;
use App\Http\Controllers\AnoController;
use App\Http\Controllers\auth\authAzureController;
use App\Http\Controllers\budgetAnnuelController;
use App\Http\Controllers\livrableController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\OrganisationCOntroller;
use App\Http\Controllers\ProgrammeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\siteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('auth/callback', [authAzureController::class, 'callback']);
Route::get('logout', [authAzureController::class, 'logout']);
Route::get('auth/user/{id}', [authAzureController::class, 'authUser']);

// Routes Users
Route::get('users', [UserController::class, 'users'])->name('users');
Route::get('users/programme/{id}', [UserController::class, 'usersProgramme'])->name('users.programme');
Route::post('insert/contributeur/', [UserController::class, 'insertContributeurs'])->name('users.insert');
Route::post('user/organisation', [UserController::class, 'userOrganisation']);

// Routes Organisations
Route::get('organisations', [OrganisationCOntroller::class, 'organisations']);
Route::get('participe/organisations', [OrganisationCOntroller::class, 'participeOrganisations']);
Route::delete('delete/organisations/{id}', [OrganisationCOntroller::class, 'deleteOrganisations']);
Route::post('insert/organisations/', [OrganisationCOntroller::class, 'insertOrganisations']);

// Route pour les sites
Route::get('sites', [siteController::class, 'sites']);
Route::post('insert/site', [siteController::class, 'insertSite']);
Route::delete('delete/site/{id}', [siteController::class, 'deleteSite']);
Route::post('edit/site', [siteController::class, 'editSite']);

// Routes Pour les Objectifs stratégiques
Route::post('insert/objectif/', [ObjectifController::class, 'insertObjectif']);
Route::get('objectifs', [ObjectifController::class, 'objectifs']);
Route::get('select/objectif/{id}', [ObjectifController::class, 'selectObjectif']);
Route::put('edit/objectif/', [ObjectifController::class, 'editObjectif']);
Route::delete('delete/objectif/{id}', [ObjectifController::class, 'deleteObjectif']);
Route::delete('delete/objectif/{id}', [ObjectifController::class, 'deleteObjectif']);
// Routes Pour les programmes
Route::get('programmes', [ProgrammeController::class, 'programmes']);
Route::post('insert/programme/', [ProgrammeController::class, 'insertProgramme']);
Route::get('select/programme/{id}', [ProgrammeController::class, 'selectProgramme']);
Route::put('edit/programme/', [ProgrammeController::class, 'editProgramme']);
Route::get('programme/planing/{id}', [ProgrammeController::class, 'gantt']);
Route::get('programme/planing/data/{id}', [ProgrammeController::class, 'planingGantt']);
Route::get('programme/site/{id}', [ProgrammeController::class, 'sites']);
Route::post('programme/insert/site', [ProgrammeController::class, 'insertSites']);
Route::delete('delete/programme/{id}', [ProgrammeController::class, 'deleteProgramme']);

// Routes Pour les budgets annuels (PTBA)
Route::post('insert/budgetannuel/', [budgetAnnuelController::class, 'insertBudgetAnnuel']);
Route::get('details/budgetannuel/{id}', [budgetAnnuelController::class, 'detailBudgetAnnuel']);
Route::get('budgetannuels/{id}', [budgetAnnuelController::class, 'budgetannuels']);
Route::get('budgetannuel/activites', [budgetAnnuelController::class, 'activites']);
Route::get('budgetannuel/activite/{id}', [budgetAnnuelController::class, 'activite']);

//Route::get('');
Route::delete('budgetannuel/delete/activite/{id}', [budgetAnnuelController::class, 'deleteActivite']);
Route::get('all/budgetannuels/', [budgetAnnuelController::class, 'allBudget']);

// Routes pour les Anos
Route::post('insert/ano/', [AnoController::class, 'insertAno']);
Route::get('ano', [AnoController::class, 'ano']);
Route::get('select/edit/ano/{id}', [AnoController::class, 'selectEditAno']);
Route::post('edit/ano', [AnoController::class, 'editAno']);
Route::delete('delete/ano/{id}', [AnoController::class, 'deleteANO']);
Route::get('detail/ano/{id}', [AnoController::class, 'detailAno']);
Route::get('ano/programme/{id}', [AnoController::class, 'anoProgramme']);
Route::post('ano/etude/{id}', [AnoController::class, 'etudeAno']);
Route::get('ano/valider/{id}', [AnoController::class, 'valider']);

// Routes Activités / jalons
Route::post('insert/activite', [ActiviteController::class, 'insertActivite']);
Route::get('activite/phases', [ActiviteController::class, 'getPhase']);
Route::get('activite/sites', [ActiviteController::class, 'getSites']);
Route::get('activite', [ActiviteController::class, 'getJalon']);

// Routes livrable
Route::post('insert/livrable', [livrableController::class, 'insertLivrable']);
Route::get('livrable/{id}', [livrableController::class, 'livrable']);

// Routes pour les contracts
Route::get('contracts/{id}', [ContractController::class, 'contracts']);
Route::post('insert/contract', [ContractController::class, 'insertContract']);
Route::get('contracts', [ContractController::class, 'getContracts']);

// Routes pour les factures 
Route::get('factures', [FactureController::class, 'factures']);
Route::post('insert/factures', [FactureController::class, 'insertFacture']);
Route::get('select/facture/{id}', [FactureController::class, 'selectFacture']);

