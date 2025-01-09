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
    public function insertLivrable(Request $request)
    {
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

        // Création du livrable
        $livrable = Livrable::create([
            'livrable' => $request->livrable,
            'user_id' => $request->responsable,
            'activite_id' => $request->activite_id,
            'programme_id' => $request->programme_id,
        ]);

        $titres = $request->input('titres');
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $destinationPath = 'assets/documents';
                $filename = uniqid() . '_' . $file->getClientOriginalName();
                $filePath = $file->move(public_path($destinationPath), $filename);
                $fileUrl = asset('https://cgpgabon24.alwaysdata.net/api-eano/public/assets/documents/' . $filename);

                $document = new DocumentsLivrable();
                $document->titre = $titres[$index];
                $document->file_name = $filename;
                $document->file_path = $destinationPath . '/' . $filename;
                $document->file_url = $fileUrl;
                $document->livrable_id = $livrable->id;
                $document->save();
            }

            return response()->json([
                'message' => 'Livrable enregistré avec succès !'
            ]);
        }

        return response()->json([
            'message' => 'Aucun livrable enregistré !'
        ], 400);
    }

    public function livrable($programme_id)
    {
        $livrables = Livrable::where('programme_id', $programme_id)
            ->with(['user', 'activite', 'documents'])
            ->get();

        $livrablesData = $livrables->map(function ($livrable) {
            return [
                'programme_id' => $livrable->programme_id,
                'id' => $livrable->id,
                'livrable' => $livrable->livrable,
                'responsable' => $livrable->user ? $livrable->user->name : null,
                'activite' => $livrable->activite ? $livrable->activite->libelle : null,
                'created_at' => $livrable->created_at,
                'updated_at' => $livrable->updated_at,
                'documents' => $livrable->documents ? $livrable->documents->map(function ($doc) {
                    return $doc->file_url;
                }) : null,
            ];
        });

        return response()->json($livrablesData, 200, [], JSON_UNESCAPED_SLASHES);
    }

    public function updateLivrable(Request $request, $id)
    {
        $livrable = Livrable::findOrFail($id);

        // Validation des données
        $request->validate([
            'livrable' => 'required',
            'activite_id' => 'required',
            'responsable' => 'required|exists:users,id',
            'programme_id' => 'required',
        ]);

        // Mise à jour du livrable
        $livrable->update([
            'livrable' => $request->livrable,
            'user_id' => $request->responsable,
            'activite_id' => $request->activite_id,
            'programme_id' => $request->programme_id,
        ]);

        return response()->json([
            'message' => 'Livrable mis à jour avec succès !',
            'livrable' => $livrable
        ]);
    }

    public function deleteLivrable($id)
    {
        $livrable = Livrable::findOrFail($id);

        // Supprimer les documents associés
        $documents = DocumentsLivrable::where('livrable_id', $id)->get();
        foreach ($documents as $document) {
            $filePath = public_path($document->file_path);
            if (file_exists($filePath)) {
                unlink($filePath); // Supprimer le fichier du serveur
            }
            $document->delete(); // Supprimer l'enregistrement de la base de données
        }

        // Supprimer le livrable
        $livrable->delete();

        return response()->json([
            'message' => 'Livrable supprimé avec succès !'
        ]);
    }
}
