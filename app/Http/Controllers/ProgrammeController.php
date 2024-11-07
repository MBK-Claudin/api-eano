<?php

namespace App\Http\Controllers;

use App\Models\objectif;
use App\Models\organisation;
use App\Models\programme;
use App\Models\site;
use App\Models\User;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function programmes (){
        $programmes = programme::with('objectif', 'users', 'organisations')->get();
        return response()->json($programmes);
    }

    public function insertProgramme(Request $request){
        $objectif = objectif::where('id', $request->objectif_id)->first();

        $programme = Programme::create([
            'libelle' => $request->libelle,
            'objectif_specifique' => $request->objectif_specifique,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
        ]);

        $objectif->programmes()->save($programme);

        $organisations = $request->input('organisation');
        $ancrages = $request->input('ancrage');
        $responsables = $request->input('responsable');
        $emails = $request->input('email');

        //dd($organisations, $ancrages);

        if($organisations){
            for($i = 0; $i < count($organisations); $i++){
                $organisation = $organisations[$i];
                $ancrage = $ancrages[$i];
    
                $isOrganisation = organisation::where('libelle', $organisation)->first();
    
                if($isOrganisation){
                    $programme->organisations()->attach($isOrganisation->id, ['ancrage' => $ancrage]);
                }else{
                    $organisation = Organisation::create([
                        'libelle' => $organisation,
                    ]);
    
                    $programme->organisations()->attach($organisation->id, ['ancrage' => $ancrage]);
                }
            }
        }

        if($responsables){
            for($i = 0; $i < count($responsables); $i++){
                $responsable = $responsables[$i];
                $email = $emails[$i];
    
                $user = User::where('email', $email)->first();
    
                if($user){
                    $user->programmes()->attach($programme->id, ['role' => 'responsable']);
                }else{
                    $photo = asset('assets/images/profile/default_user.png');
                    $user = User::create([
                        'name' => $responsable,
                        'email' => $email,
                        'photo_url' => $photo,
                    ]);
    
                    $user->programmes()->attach($programme->id, ['role' => 'responsable']);
                }
            }
        }

        return response()->json($programme);
    }

    public function selectProgramme($id){
        $programme = Programme::with('objectif', 'organisations', 'users')->find($id);
        return response()->json($programme);
    }

    public function editProgramme(Request $request){
        //dd($request->all());

        $objectif = objectif::where('id', $request->objectif_id)->first();

        $programme = Programme::find($request->id);
        $programme->objectif_id = $objectif->id;
        $programme->objectif_specifique = $request->objectif_specifique;
        $programme->libelle = $request->libelle;
        $programme->date_debut = $request->date_debut;
        $programme->date_fin = $request->date_fin;
        $programme->save();

        $programme->organisations()->detach();
        $programme->users()->detach();  

        $organisations = $request->input('organisation');
        $ancrages = $request->input('ancrage');
        $responsables = $request->input('responsable');
        $emails = $request->input('email');

        //dd($organisations, $ancrages);

        if($organisations){
            for($i = 0; $i < count($organisations); $i++){
                $organisation = $organisations[$i];
                $ancrage = $ancrages[$i];
    
                $isOrganisation = Organisation::where('libelle', $organisation)->first();
    
                if($isOrganisation){
                    $programme->organisations()->attach($isOrganisation->id, ['ancrage' => $ancrage]);
                }else{
                    $organisation = Organisation::create([
                        'libelle' => $organisation,
                    ]);
    
                    $programme->organisations()->attach($organisation->id, ['ancrage' => $ancrage]);
                }
            }
        }

        if($responsables){
            for($i = 0; $i < count($responsables); $i++){
                $responsable = $responsables[$i];
                $email = $emails[$i];
    
                $user = User::where('name', $responsable)->first();
    
                if($user){
                    $user->programmes()->attach($programme->id, ['role' => 'responsable']);
                }else{
                    $user = User::create([
                        'name' => $responsable,
                        'email' => $email,
                    ]);
    
                    $user->programmes()->attach($programme->id, ['role' => 'responsable']);
                }
            }
        }

        return response()->json($programme);
    }

    public function gantt($id){
        $planing = programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites')->find($id);
        return response()->json($planing->budgetannuels);
    }

    public function sites ($id) {

        $livrables = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.sites')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->activites;
                    });
                });
            });
        });

        return response()->json($livrables);
    }

    public function planingGantt($id){

        $data = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.phase')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel;
                });
            });
        });

        return response()->json($data);
    }

    public function insertSites(Request $request){
        $site = site::create([
            'libelle' => $request->site,
            'province' => $request->province,
            'departement' => $request->departement,
            'ville' => $request->ville,
            'coordonnees_gps' => $request->coordonnee,
            'commentaire' => $request->commentaire
        ]);

        return response()->json($site);
    }

    public function anos ($id){

        $ano = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.evenements.ano')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->evenements->flatMap(function($evenements){
                            return $evenements->ano;
                        });
                    });
                });
            });
        });

        return response()->json($ano);
    }

    public function livrable($id) {
        $ano = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.livrables')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->activites->flatMap(function($activites){
                            return $activites->livrables;
                        });
                    });
                });
            });
        });

        return response()->json($ano);
    }

    public function factures ($id){
        $ano = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.anos.factures')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel;
                });
            });
        });

        return response()->json($ano);
    }

    public function deleteProgramme($id){
        $programme = programme::find($id);
        $programme->delete();
        return response()->json($programme);
    }

    public function trasnformation ($id) {
        $ano = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.sites')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->activites;
                    });
                });
            });
        });

        return response()->json($ano);
    }

}
