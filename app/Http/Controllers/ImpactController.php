<?php

namespace App\Http\Controllers;

use App\Models\impact;
use Illuminate\Http\Request;

class ImpactController extends Controller
{
    public function index($programmeId)
    {
        // Récupérer un impact spécifique avec ses relations
        $impact = Impact::with(['programme', 'site', 'activite'])
        ->where('programme_id', $programmeId)
        ->get();
        // Si l'impact n'est pas trouvé, retourner une réponse d'erreur
        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Transformer les données pour les retourner sous un format spécifique
        $impactData = $impact->map(function ($impact) {
            return [
                'programme_id' => $impact->programme_id,
                'id' => $impact->id,
                'type_impact' => $impact->type_impact,
                'libelle_impact' => $impact->libelle_impact,
                'force' => $impact->force,
                'site' => $impact->site_id ? $impact->site : null,
                'taille' => $impact->taille,
                'mitigation' => $impact->mitigation,
                'activite' => $impact->activite_id ? $impact->activite : null,
                'created_at' => $impact->created_at,
                'updated_at' => $impact->updated_at,


        ];
    });
        return response()->json($impactData);

    }

    // Stocker un nouvel impact
    public function store(Request $request)
    {
        $impact = Impact::create([
            'type_impact' => $request->type_impact,
            'libelle_impact' => $request->libelle_impact,
            'force' => $request->force,
            'site_id' => $request->site_id ,
            'taille' => $request->taille,
            'mitigation' => $request->mitigation,
            'programme_id' => $request->programme_id,
            'activite_id' => $request->activite_id

        ]);

        return response()->json($impact, 201);
    }

    // Afficher un impact spécifique
    public function show($id)
    {
        $impact = Impact::with(['programme', 'site', 'activite'])->find($id);

        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        return response()->json([
            'programme_id' => $impact->programme_id,
            'id' => $impact->id,
            'type_impact' => $impact->type_impact,
            'libelle_impact' => $impact->libelle_impact,
            'force' => $impact->force,
            'site' => $impact->site_id ? $impact->site : null,
            'taille' => $impact->taille,
            'mitigation' => $impact->mitigation,
            'activite' => $impact->activite_id ? $impact->activite : null,
            'created_at' => $impact->created_at,
            'updated_at' => $impact->updated_at,

        ]);
    }
    // Mettre à jour un impact
    public function update(Request $request, $id)
    {
        // Trouver l'impact à mettre à jour
        $impact = Impact::find($id);

        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Récupérer uniquement les champs envoyés dans la requête
        $impact->fill($request->only([
            'type_impact',
            'libelle_impact',
            'force',
            'site_id',
            'taille',
            'mitigation',
            'programme_id',
            'activite_id',
        ]));

        // Sauvegarder les modifications
        $impact->save();

        return response()->json([
            'message' => 'Impact mis à jour avec succès',
            'data' => $impact,
        ]);
    }


    // Supprimer un impact
    public function destroy($id)
    {
        // Trouver l'impact à supprimer
        $impact = Impact::find($id);

        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Supprimer l'impact
        $impact->delete();

        // Retourner une réponse indiquant que la suppression a réussi
        return response()->json(['message' => 'Impact supprimé avec succès'], 200);
    }
}
