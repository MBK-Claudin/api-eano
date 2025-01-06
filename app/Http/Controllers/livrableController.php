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

        // Validation des données
        $request->validate([
            'documents' => 'required',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'livrable' => 'required',
            'activite_id' => 'required',
            'responsable' => 'required|exists:users,id',
            'programme_id' => 'required',
            'titres' => 'required',
        ]);

        // Création du livrable avec l'ID du responsable
        $livrable = Livrable::create([
            'livrable' => $request->livrable,
            'user_id' => $request->responsable,  // Ici on prend l'ID du responsable
            'activite_id' => $request->activite_id,
            'programme_id' => $request->programme_id,

        ]);

        $livrable->save();

        $titres = $request->input('titres');

        if ($request->hasFile('documents')) {
            // Traitement des fichiers envoyés
            foreach ($request->file('documents') as $index => $file) {

                // Définir le chemin de destination pour les documents
                $destinationPath = ' api-eano/public/assets/documents';  // Chemin dans le répertoire public
                // Sauvegarder le fichier dans le répertoire public/assets/documents avec un nom unique
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->move(public_path($destinationPath), $filename);

                // Utiliser la fonction asset() pour générer l'URL publique du fichier
                $fileUrl = asset(' api-eano/public/assets/documents/' . $filename);

                // Enregistrer les informations du document dans la base de données
                $document = new DocumentsLivrable();
                $document->titre = $titres[$index];  // Titres associés au fichier
                $document->file_name = $filename;
                $document->file_path = $destinationPath . '/' . $filename;
                $document->file_url = $fileUrl;  // URL générée avec asset()
                $document->livrable_id = $livrable->id;  // Associer le document au livrable
                $document->save();  // Sauvegarder le document
            }

            // Retourner un message de succès
            return response()->json([
                'message' => 'Livrable enregistré avec succès !'
            ]);
        }

        // Si aucun fichier n'est présent
        return response()->json([
            'message' => 'Aucun livrable enregistré !'
        ], 400);
    }

    // public function livrable($id){

    //     $livrables = Programme::with(
    //         '')
    //     ->find($id)
    //     ->budgetannuels
    //     ->flatMap(function($budgetannuels) {
    //         return $budgetannuels->composants->flatMap(function($composants) {
    //             return $composants->souscomposants->flatMap(function($souscomposants) {
    //                 return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
    //                     return $activitesbudgetannuel->activites;
    //                 });
    //             });
    //         });
    //     });
    //     return response()->json($livrables);

    // }




    public function livrable($programme_id)
    {
        // Récupérer les livrables associés à un programme donné
        $livrables = Livrable::where('programme_id', $programme_id)
                             ->with(['user', 'activite', 'documents'])  // Charger les relations user, activite, et documents
                             ->get();

        // Formater les données pour inclure les informations de l'utilisateur et de l'activité
        $livrablesData = $livrables->map(function ($livrable) {
            return [
                'programme_id' => $livrable->programme_id,
                'id' => $livrable->id,
                'livrable' => $livrable->livrable,
                'responsable' => $livrable->user ? $livrable->user->name : null,  // Afficher le nom de l'utilisateur
                'activite' => $livrable->activite ? $livrable->activite->libelle : null,  // Afficher le libellé de l'activité
                'created_at' => $livrable->created_at,
                'updated_at' => $livrable->updated_at,
                'documents' => $livrable->documents ? $livrable->documents->map(function ($doc) {
                    return $doc->file_url;
                }) : null,  // Les documents associés
            ];
        });

        // Retourner les livrables avec les informations enrichies
        return response()->json($livrablesData, 200, [], JSON_UNESCAPED_SLASHES);

    }


}
