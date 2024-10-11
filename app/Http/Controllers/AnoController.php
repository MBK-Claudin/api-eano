<?php

namespace App\Http\Controllers;

use App\Models\activiteBudgetAnnuel;
use App\Models\ano;
use App\Models\documentAno;
use App\Models\evenement;
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
            'budget' => 'required',
            'email' => 'required',
            'responsable' => 'required',
            'evenement' => 'required',
        ]);

        
        //$activite = activiteBudgetAnnuel::find($request->activite_id)->first();
        $ano = ano::create([
            'budget' => $request->budget,
            'statut' => 'En Attente'
        ]);

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

        //dd($documents);
        //return response()->json($documents);
        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                // Récupérer le chemin d'accès au fichier
                $fileUrl = Storage::url($filePath);
    
                // Enregistrer les informations du document en base de données
                $document = new documentAno();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
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
        $ano = ano::with('evenements.users')->get();
        return response()->json($ano);
    }

    public function selectEditAno ($id){
        //dd($id);
        $ano = ano::with('documents', 'evenements', 'users')->find($id);
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
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                // Récupérer le chemin d'accès au fichier
                $fileUrl = Storage::url($filePath);

                $lastDoc = documentAno::where('titre', $titre)->first();
                if($lastDoc){
                    $lastDoc->titre = $titre;
                    $lastDoc->file_name = $file->getClientOriginalName();
                    $lastDoc->file_path = $filePath;
                    $lastDoc->file_url = $fileUrl;
                    $lastDoc->save();

                }else {
                    $document = new documentAno();
                    $document->titre = $titre;
                    $document->file_name = $file->getClientOriginalName();
                    $document->file_path = $filePath;
                    $document->file_url = $fileUrl;
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
        $ano = ano::with('documents', 'evenements.users', 'users', 'documents')->find($id);
        return response()->json($ano);
    }
}
