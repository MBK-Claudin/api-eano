<?php

namespace App\Http\Controllers;

use App\Models\documentsLivrable;
use App\Models\livrable;
use App\Models\programme;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class livrableController extends Controller
{
    public function insertLivrable(Request $request){

        $request->validate([
            'documents' => 'required',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'livrable' => 'required',
            'activite_id' => 'required',
            'email' => 'required',
            'responsable' => 'required',
            'titres' => 'required',
        ]);
        
        //$activite = activiteBudgetAnnuel::find($request->activite_id)->first();
        $livrable = livrable::create([
            'livrable' => $request->livrable
        ]);
        
        $livrable->activite_id = $request->activite_id;
        $livrable->save();

        $emails = $request->input('email');
        $titres = $request->input('titres');

        if($emails){
            for ($i = 0; $i < count($emails); $i++){
                $user = User::where('email', $emails[$i])->first();
                $user->livrables()->attach($livrable->id, ['role' => 'Responsable']);
            }
        }

        //dd($documents);
        //return response()->json($documents);
        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                //dd($request->file('documents'));
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                // Récupérer le chemin d'accès au fichier
                $fileUrl = Storage::url($filePath);
    
                // Enregistrer les informations du document en base de données
                $document = new documentsLivrable();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
                $document->livrable_id = $livrable->id;
                $document->save();
            }
            return response()->json([
                'message' => 'livrable enregistrer !'
            ]);
        }

        return response()->json([
            'message' => 'livrable non enregistrer !'
        ], 400);
    }

    public function livrable($id){

        $livrables = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.livrables')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->activites->flatMap(function($activites){
                            return $activites->livrables->flatMap(function($livrables){
                                return [
                                    'livrable' => $livrables,
                                    'activite' => $livrables->activite,
                                    'sites' => $livrables->activite->sites
                                ];
                            });
                        });
                    });
                });
            });
        });

        return response()->json($livrables);

    }
}
