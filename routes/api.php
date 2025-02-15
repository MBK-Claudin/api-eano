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
use App\Http\Controllers\ImpactController;
use App\Http\Controllers\FinancementController;
use App\Http\Controllers\siteController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\CollectController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('auth/callback', [authAzureController::class, 'callback']);
Route::get('logout', [authAzureController::class, 'logout']);
Route::get('auth/user/{id}', [authAzureController::class, 'authUser']);
Route::get('user/activitebudgetannuel/{id}', [UserController::class, 'userActivitebudgetannuel']);
Route::get('user/affectations/{id}', [UserController::class, 'affectations']);
Route::get('user/taches/{id}', [UserController::class, 'taches']);

// Routes Users
Route::get('users', [UserController::class, 'users'])->name('users');
Route::put('user/updateprogramme/{id}', [UserController::class,'updateContributeurs']);
Route::get('users/programme/{id}', [UserController::class, 'usersProgramme'])->name('users.programme');
Route::get('/programmes/{programme_id}/users/{user_id}', [UserController::class, 'removeContributeurFromProgramme'])->name('users.delete');

Route::post('insert/contributeur/', [UserController::class, 'insertContributeurs'])->name('users.insert');
Route::post('login/mail/', [UserController::class, 'logins']);

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
Route::get('/sites/from-kobo', [SiteController::class, 'storeFromKoboData']);

// Routes Pour les Objectifs stratégiques
Route::post('insert/objectif/', [ObjectifController::class, 'insertObjectif']);
Route::get('objectifs', [ObjectifController::class, 'objectifs']);
Route::get('select/objectif/{id}', [ObjectifController::class, 'selectObjectif']);
Route::put('edit/objectif/', [ObjectifController::class, 'editObjectif']);
Route::delete('delete/objectif/{id}', [ObjectifController::class, 'deleteObjectif']);
Route::delete('delete/objectif/{id}', [ObjectifController::class, 'deleteObjectif']);
Route::get('objectif/programme/{id}', [ObjectifController::class, 'objectifProgramme']);

// Routes Pour les programmes
Route::get('programmes', [ProgrammeController::class, 'programmes']);
Route::post('insert/programme/', [ProgrammeController::class, 'insertProgramme']);
Route::get('select/programme/{id}', [ProgrammeController::class, 'selectProgramme']);
Route::put('edit/programme/', [ProgrammeController::class, 'editProgramme']);
Route::get('programme/facture/{id}', [ProgrammeController::class, 'factures']);

//Route::get('programme/planing/{id}', [ProgrammeController::class, 'gantt']);
Route::get('programme/planing/data/{id}', [ProgrammeController::class, 'planingGantt']);
Route::get('programme/site/{id}', [ProgrammeController::class, 'sites']);
Route::post('programme/insert/site', [ProgrammeController::class, 'insertSites']);
Route::delete('delete/programme/{id}', [ProgrammeController::class, 'deleteProgramme']);
Route::get('programme/planTrasnformation/{id}', [ProgrammeController::class, 'trasnformation']);

// Routes Pour les budgets annuels (PTBA)
Route::post('insert/budgetannuel', [budgetAnnuelController::class, 'insertBudgetAnnuel']);
Route::get('details/budgetannuel/{id}', [budgetAnnuelController::class, 'detailBudgetAnnuel']);
Route::get('budgetannuels/{id}', [budgetAnnuelController::class, 'budgetannuels']);
Route::get('budgetannuel/activites', [budgetAnnuelController::class, 'activites']);
Route::get('budgetannuel/activite/{id}', [budgetAnnuelController::class, 'activite']);
Route::delete('budgetannuel/delete/{id}', [budgetAnnuelController::class, 'deleteBudget']);


//Route::get('');
Route::delete('budgetannuel/delete/activite/{id}', [budgetAnnuelController::class, 'deleteActivite']);
Route::get('all/budgetannuels', [budgetAnnuelController::class, 'allBudget']);

Route::post('insert/ano', [AnoController::class, 'insertAno']);
Route::get('get/ano', [AnoController::class, 'ano']);
Route::get('select/edit/ano/{id}', [AnoController::class, 'selectEditAno']);
Route::post('edit/ano', [AnoController::class, 'editAno']);
Route::delete('delete/ano/{id}', [AnoController::class, 'deleteANO']);
Route::get('detail/ano/{id}', [AnoController::class, 'detailAno']);
Route::get('ano/programme/{id}', [AnoController::class, 'anoProgramme']);
Route::post('ano/etude/{id}', [AnoController::class, 'etudeAno']);
Route::get('ano/valider/{id}', [AnoController::class, 'valider']);
Route::get('ano/activitebudgetannuel/{id}', [AnoController::class, 'anoActivite']);
Route::get('ano/programme/composantes/activite/{id}', [AnoController::class,'anoComposantesActivites']);

// Routes Activités / jalons
Route::post('insert/activite', [ActiviteController::class, 'insertActivite']);
Route::get('activite/phases', [ActiviteController::class, 'getPhase']);
Route::get('activite/sites', [ActiviteController::class, 'getSites']);
Route::get('activite', [ActiviteController::class, 'getJalon']);
Route::get('activite/activitebudgetannuel/{id}', [ActiviteController::class, 'jalonActivite']);

// Routes livrable
Route::post('insert/livrable', [livrableController::class, 'insertLivrable']);
Route::get('livrable/{id}', [livrableController::class, 'livrable']);
Route::delete('livrable/delete/{id}', [livrableController::class, 'deleteLivrable']);
Route::put('livrable/update/{id}', [livrableController::class, 'updateLivrable']);

// Routes pour les contracts
Route::get('contracts/{id}', [ContractController::class, 'contracts']);
Route::get('contracts/programme/{id}', [ContractController::class, 'contractProgramme']);
Route::post('insert/contract', [ContractController::class, 'insertContract']);
Route::get('contracts', [ContractController::class, 'getContracts']);

// Routes pour les factures
Route::get('factures', [FactureController::class, 'factures']);
Route::post('insert/factures', [FactureController::class, 'insertFacture']);
Route::get('select/facture/{id}', [FactureController::class, 'selectFacture']);
Route::get('facture/ano', [FactureController::class, 'anos']);
Route::get('facture/contract', [FactureController::class, 'contracts']);
Route::get('facture/etatActuel/{id}', [FactureController::class, 'etatActuel']);
Route::get('facture/traitement/{idFacture}/{idService}/{user_id}', [FactureController::class, 'traitementFacture']);

//route pour les impacts
Route::get('impacts/{programme_id}', [ImpactController::class, 'index']);
Route::post('impacts/insert', [ImpactController::class, 'store']);
Route::put('impacts/update/{id}', [ImpactController::class, 'update']);
Route::delete('impact/delete/{id}', [ImpactController::class, 'destroy']);
Route::get('impacts/show/{id}', [ImpactController::class, 'show']);


//Route pour les missions
    Route::get('mission/{programme_id}', [MissionController::class, 'getMission']);
    Route::get('mission/show/{id}', [MissionController::class, 'show']);
    Route::post('mission/insert', [MissionController::class, 'store']);
    Route::put('mission/update/{id}', [MissionController::class, 'update']);
    Route::delete('mission/delete/{id}', [MissionController::class, 'destroy']);
    Route::post('collect/requete', [CollectController::class, 'import']);
    Route::get('collect/visuel/{id}', [CollectController::class, 'index']);


//route pour le financement
Route::get('financement/{programme_id}', [FinancementController::class, 'index']);
Route::post('/financement/insert', [FinancementController::class, 'store']);
Route::get('financement/show/{id}', [FinancementController::class, 'show']);
Route::put('financement/update/{id}', [FinancementController::class, 'update']);
Route::delete('financement/delete/{id}', [FinancementController::class, 'destroy']);

