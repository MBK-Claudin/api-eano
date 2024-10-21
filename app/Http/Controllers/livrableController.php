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

                $destinationPath = 'assets/documents';  // Chemin dans le répertoire public

                // Sauvegarder le fichier dans le répertoire public/assets/documents avec le nom original
                $filePath = $file->move(public_path($destinationPath), $file->getClientOriginalName());
    
                // Récupérer le nom du fichier
                $filename = $file->getClientOriginalName();
                
                // Utiliser la fonction asset() pour générer l'URL publique du fichier
                $fileUrl = asset('assets/documents/' . $filename);
    
                // Enregistrer les informations du document dans la base de données
                $document = new documentsLivrable();
                $document->titre = $titres[$index];
                $document->file_name = $filename;
                $document->file_path = $destinationPath . '/' . $filename;
                $document->file_url = $fileUrl;  // URL générée avec asset()
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

        $livrables = Programme::with(
            'budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.livrables.users', 'budgetannuels.composants.souscomposants.activitesbudgetannuel.activites.phase')
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
}
