<?php

namespace App\Http\Controllers;

use App\Models\activiteBudgetAnnuel;
use App\Models\ano;
use App\Models\documentAno;
use App\Models\evenement;
use App\Models\programme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnoController extends Controller
{
    public function insertAno(Request $request){

        $request->validate([
            'documents' => 'required|',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'titres' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'libelle' => 'required',
            'budget' => 'required',
            'email' => 'required',
            'responsable' => 'required',
            'evenement' => 'required',
        ]);

        
        //$activite = activiteBudgetAnnuel::find($request->activite_id)->first();
        $ano = ano::create([
            'libelle'=> $request->libelle,
            'budget' => $request->budget,
            'statut' => 'En Attente'
        ]);

        if($request->input('activite_id')){
            $ano->activite_budget_annuel_id = $request->activite_id;
            $ano->save();
        }

        $ano->users()->attach($request->user_id, ['action' => 'creation']);

        $evenements = $request->input('evenement');
        $date_debut = $request->input('date_debut');
        $date_fin = $request->input('date_fin');
        $emails = $request->input('email');
        $titres = $request->input('titres');

        if($evenements){
            
            for ($i = 0; $i < count($evenements); $i++) {
                
                $event = $evenements[$i];
                $date_d = $date_debut[$i];
                $date_f = $date_fin[$i];

                $newEvent = evenement::create([
                    'libelle' => $event,
                    'date_debut' => $date_d,
                    'date_fin' => $date_f,
                ]);
                
                $newEvent->ano_id = $ano->id;
                $newEvent->activite_budget_annuel_id = $request->activite_id;
                $newEvent->save();
            }
        }

        if($emails){
            for($i = 0; $i < count($emails); $i++){
                $user = User::where('email', $emails[$i])->first();
                $user->anos()->attach($ano->id, ['role'=> 'Responsable']);
            }
        }

        //dd($documents);
        //return response()->json($documents);
        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){

                $destinationPath = 'assets/documents';  // Chemin dans le répertoire public

                // Sauvegarder le fichier dans le répertoire public/assets/documents avec le nom original
                $filePath = $file->move(public_path($destinationPath), $file->getClientOriginalName());
    
                // Récupérer le nom du fichier
                $filename = $file->getClientOriginalName();
                
                // Utiliser la fonction asset() pour générer l'URL publique du fichier
                $fileUrl = asset('assets/documents/' . $filename);
    
                // Enregistrer les informations du document dans la base de données
                $document = new documentAno();
                $document->titre = $titres[$index];
                $document->file_name = $filename;
                $document->file_path = $destinationPath . '/' . $filename;
                $document->file_url = $fileUrl;  // URL générée avec asset()
                $document->ano_id = $ano->id;
                $document->save();
            }

            return response()->json([
                'message' => 'ano enregistrer !'
            ], 200);
        }

        return response()->json([
            'message' => 'ano non enregistrer !'
        ], 400);

    }

    public function ano () {
        $ano = ano::with('evenements', 'users', 'activitebudgetannuel')->get();
        return response()->json($ano);
    }

    public function selectEditAno ($id){
        $ano = ano::with('documents', 'evenements.users', 'users')->find($id);
        return response()->json($ano);
    }

    public function editAno (Request $request) {


        $ano = ano::updateOrCreate([
                'id' => $request->ano_id,
            ],[
                'budget' => $request->budget,
        ]);

        $ano->users()->detach();

        $ano->users()->attach($request->user_id);

        $evenements = $request->input('evenement');
        $date_debut = $request->input('date_debut');
        $date_fin = $request->input('date_fin');
        $emails = $request->input('email');
        $titres = $request->input('titres');

        if($evenements){
            
            for ($i = 0; $i < count($evenements); $i++) {
                
                $event = $evenements[$i];
                $date_d = $date_debut[$i];
                $date_f = $date_fin[$i];
                $email = $emails[$i];

                $user = User::where('email', $email)->first();

                $lastevent = evenement::where('libelle', $event)->first();

                if($lastevent){
                    $lastevent->libelle = $event;
                    $lastevent->date_fin = $date_f;
                    $lastevent->date_debut = $date_d;
                    
                    $lastevent->ano_id = $ano->id;
                    $lastevent->activite_budget_annuel_id = $request->activite_id;
                    $lastevent->save();

                    $user->evenements()->detach($lastevent->id, ['role' => 'Responsable']);

                    $user->evenements()->attach($lastevent->id, ['role' => 'Responsable']);
                }else {
                    $newEvent = evenement::create([
                        'libelle' => $event,
                        'date_debut' => $date_d,
                        'date_fin' => $date_f,
                    ]);

                    $newEvent->ano_id = $ano->id;
                    $newEvent->activite_budget_annuel_id = $request->activite_id;
                    $newEvent->save();

                    $user->evenements()->attach($newEvent->id, ['role' => 'Responsable']);

                }
            }
        }

        //dd($documents);
        //return response()->json($documents);
        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                $destinationPath = 'assets/documents';  // Chemin dans le répertoire public

                // Sauvegarder le fichier dans le répertoire public/assets/documents avec le nom original
                $filePath = $file->move(public_path($destinationPath), $file->getClientOriginalName());
        
                // Récupérer le nom du fichier
                $filename = $file->getClientOriginalName();
                
                // Utiliser la fonction asset() pour générer l'URL publique du fichier
                $fileUrl = asset('assets/documents/' . $filename);

                $lastDoc = documentAno::where('titre', $titres[$index])->first();
                if($lastDoc){
                    $lastDoc->titre = $titres[$index];
                    $lastDoc->file_name = $filename;
                    $lastDoc->file_path = $destinationPath . '/' . $filename;
                    $lastDoc->file_url = $fileUrl;  // URL générée avec asset()
                    $lastDoc->ano_id = $ano->id;
                    $lastDoc->save();

                }else {
                    $document = new documentAno();
                    $document->titre = $titres[$index];
                    $document->file_name = $filename;
                    $document->file_path = $destinationPath . '/' . $filename;
                    $document->file_url = $fileUrl;  // URL générée avec asset()
                    $document->ano_id = $ano->id;
                    $document->save();
                }
            }

            return response()->json([
                'message' => 'ano modifier !'
            ], 200);
        }

        return response()->json([
            'message' => 'ano non modifier !'
        ], 400);
    }

    public function deleteANO ($id){
        $ano = ano::find($id);
        $ano->delete();
        return response()->json([
            'messge' => 'Suppression effectuer avec succès !'
        ]);
    }

    public function detailAno($id) {
        $ano = ano::with('documents', 'evenements', 'users', 'activitebudgetannuel')->find($id);
        return response()->json($ano);
    }

    public function anoProgramme($id){
        $ano = programme::with(
            'budgetannuels.composants.souscomposants.activitesbudgetannuel.anos.evenements',
        'budgetannuels.composants.souscomposants.activitesbudgetannuel.anos.users')
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
    public function anoComposantesActivites($id) {
        $activites = programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel')
        ->find($id)
        ->budgetannuels->flatMap(function($budgetannuels){
            return $budgetannuels->composants;
        });

        return response()->json($activites);
    }

    public function etudeAno($id, Request $request){
        
        $request->validate([
            'budget_cntippee' => 'required',
            'situation_actuelle'=> 'required',
            'situation_avenir' => 'required',
            'commentaire' => 'required',
        ]);

        $ano = ano::find($id);

        $ano->budget_cntippee = $request->budget_cntippee;
        $ano->situation_sctuelle = $request->situation_actuelle;
        $ano->situation_venir = $request->situation_avenir;
        $ano->commentaire = $request->commentaire;
        $ano->statut = 'En traitement';
        $ano->save();
        
        return response()->json($ano);
    }

    public function valider($id){
        $ano = ano::find($id);
        $ano->statut = 'Validé';
        return response()->json($ano);
    }

    public function anoActivite ($id) {
        $activite = activiteBudgetAnnuel::with('anos.evenements.users')->find($id);
        return response()->json($activite->anos);
    }

}
