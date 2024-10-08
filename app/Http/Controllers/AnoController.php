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
            'budget' => $request->budget
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
                $document->save();

                $document->anos()->attach($ano->id);
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
}
