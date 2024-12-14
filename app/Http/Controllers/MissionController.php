<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    // Afficher toutes les missions
    public function index($programmeId)
    {
        $missions = Mission::with(['programme','activite', 'users', 'sites'])
        ->where('programme_id', $programmeId)
        ->get();

        if (!$mission) {
            return response()->json(['message' => 'mission non trouvé'], 404);
        }

        $missionData = $missions->map(function ($mission) {
            return [
            'programme_id' => $mission->programme_id,
            'id' => $mission->id,
            'description' => $mission->description,
            'objectif' => $mission->objectif,
            'date_debut' => $mission->date_debut,
            'programme' => $mission->programme_id,
            'statut' => $mission->statut,
            'activite' => $mission->activite_id ? $mission->activite->libelle : null,
            'responsable' => $mission->user_id ? $mission->user->name : null,

        ];
    });
        return response()->json($missionData);
    }

    // Afficher une mission spécifique
    public function show($id)
    {
        $mission = Mission::with(['activite', 'users', 'sites'])->find($id);

        if (!$mission) {
            return response()->json(['message' => 'Mission not found'], 404);
        }

        return response()->json($mission);
    }



    // Créer une nouvelle mission
    public function store(Request $request)
    {
        $mission = Mission::create([

            'libelle' => $request->libelle,
            'description' => $request->description,
            'objectif' => $request->objectif,
            'date_debut' => $request->date_debut ,
            'statut' => $request->statut,
            'site_id' => $request->site_id,
            'programme_id' => $request->programme_id,
            'activite_id' => $request->activite_id,
            'user_id' => $request->user_id

        ]);

        return response()->json($mission, 201);
    }



    public function update(Request $request, $id)
    {
        // Valider les données reçues
        $validatedData = $request->validate([
            'libelle' => 'string|max:255',
            'description' => 'string',
            'objectif' => 'string',
            'activite_id' => 'string',
            'user_id' => 'string',
            'site_id' => 'string',
            'statut'=> 'string'
        ]);

        // Trouver l'impact à mettre à jour
        $impact = Impact::find($id);

        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Mettre à jour les données de l'impact
        $impact->update($validatedData);

        // Retourner la réponse avec l'impact mis à jour
        return response()->json([
            'message' => 'Impact mis à jour avec succès',
            'data' => $impact,
        ]);
    }


    // Supprimer une mission
    public function destroy($id)
    {
        // Trouver l'impact à supprimer
        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Supprimer l'impact
        $mission->delete();

        // Retourner une réponse indiquant que la suppression a réussi
        return response()->json(['message' => 'Impact supprimé avec succès'], 200);
    }
}
