<?php

namespace App\Http\Controllers;

use App\Models\objectif;
use App\Models\organisation;
use App\Models\User;
use Illuminate\Http\Request;

class ObjectifController extends Controller
{

    public function objectifProgramme($id){
        $programme = objectif::with("programmes")->findOrFail($id);
        return response()->json($programme->programmes);
    }
    
    public function objectifs (){
        $objectifs = objectif::with('organisations', 'users')->get();
        return response()->json($objectifs);
    }

    public function insertObjectif(Request $request) {

        $objectif = Objectif::create([
            'secteur' => $request->secteur,
            'objectif' => $request->objectif,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'description' => $request->description,
        ]);

        $organisations = $request->input('organisation');
        $ancrages = $request->input('ancrage');
        $responsables = $request->input('responsable');
        $emails = $request->input('email');
        $roles = $request->input('role');
        $userOrganisations = $request->input('userOrganisation');
        $postes = $request->input('poste');

        //dd($organisations, $ancrages);

        if($organisations){
            for($i = 0; $i < count($organisations); $i++){
                $organisation = $organisations[$i];
                $ancrage = $ancrages[$i];
    
                $isOrganisation = organisation::where('libelle', $organisation)->first();
    
                if($isOrganisation){
                    $objectif->organisations()->attach($isOrganisation->id, ['ancrage' => $ancrage]);
                }else{
                    $organisation = organisation::create([
                        'libelle' => $organisation,
                    ]);
                    $objectif->organisations()->attach($organisation->id, ['ancrage' => $ancrage]);
                }
            }
        }

        if($responsables){
            for($i = 0; $i < count($responsables); $i++){
                $responsable = $responsables[$i];
                $email = $emails[$i];
                $role = $roles[$i];
                $userOrganisation = $userOrganisations[$i];
                $poste = $postes[$i];
    
                $user = User::where('email', $email)->first();
    
                if($user){
                    $user->objectifs()->attach($objectif->id, ['role' => $role]);
                }else{
                    $user = User::create([
                        'name' => $responsable,
                        'email' => $email,
                    ]);

                    $org = organisation::where('libelle', $userOrganisation)->first();
    
                    $user->objectifs()->attach($objectif->id, ['role' => $role]);
                    $user->organisations()->attach($org->id, ['poste' => $poste]);
                }
            }
        }
        $newobjectif = objectif::with('organisations', 'users')->find($objectif->id);
        return response()->json($newobjectif);
    }

    public function selectObjectif($id) {
        $objectif = objectif::with('organisations', 'users.organisations', 'programmes')->find($id);
        return response()->json($objectif);
    }

    public function selectEditObjectif($id) {
        $objectif = objectif::with('organisations', 'users.organisations')->find($id);
        return response()->json($objectif);
    }

    public function editObjectif(Request $request){

        $objectif = objectif::find($request->id);
        $objectif->secteur = $request->secteur;
        $objectif->objectif = $request->objectif;
        $objectif->date_debut = $request->date_debut;
        $objectif->date_fin = $request->date_fin;
        $objectif->save();

        $objectif->organisations()->detach();
        $objectif->users()->detach();  

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
                    $objectif->organisations()->attach($isOrganisation->id, ['ancrage' => $ancrage]);
                }else{
                    $organisation = Organisation::create([
                        'libelle' => $organisation,
                    ]);
    
                    $objectif->organisations()->attach($organisation->id, ['ancrage' => $ancrage]);
                }
            }
        }

        if($responsables){
            for($i = 0; $i < count($responsables); $i++){
                $responsable = $responsables[$i];
                $email = $emails[$i];
    
                $user = User::where('name', $responsable)->first();
    
                if($user){
                    $user->objectifs()->attach($objectif->id, ['role' => 'responsable']);
                }else{
                    $user = User::create([
                        'name' => $responsable,
                        'email' => $email,
                    ]);
    
                    $user->objectifs()->attach($objectif->id, ['role' => 'responsable']);
                }
            }
        }

        return response()->json($objectif);
    }

    public function deleteObjectif($id){
        $objectif = Objectif::find($id);
        $objectif->delete();
        return response()->json(['message' => 'Objectif supprimé avec succès']);
    }
}
