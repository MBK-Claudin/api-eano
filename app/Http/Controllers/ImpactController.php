<?php

namespace App\Http\Controllers;

use App\Models\impact;
use Illuminate\Http\Request;

class ImpactController extends Controller
{
    public function index($id)
    {
        // Récupérer un impact spécifique avec ses relations
        $impact = Impact::with(['programme', 'site', 'activiteBudgetAnnuel'])->find($id);

        // Si l'impact n'est pas trouvé, retourner une réponse d'erreur
        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Transformer les données pour les retourner sous un format spécifique
        $impactData = [
            'id' => $impact->id,
            'type_impact' => $impact->type_impact,
            'libelle_impact' => $impact->libelle_impact,
            'force' => $impact->force,
            'site_id' => $impact->site_id ? $impact->site->libelle : null,
            'taille' => $impact->taille,
            'mitigation' => $impact->mitigation,
            'programme_id' => $impact->programme_id,
            'activite_budget_annuel_id' => $impact->activite_budget_annuel_id ? $impact->activiteBudgetAnnuel->libelle : null
        ];

        return response()->json($impactData);
    }

    // Stocker un nouvel impact
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'programme_id' => 'required|exists:programmes,id',
            'site_id' => 'required|exists:sites,id',
            'activite_ptba_id' => 'required|exists:activites_ptba,id',
        ]);

        $impact = Impact::create($validatedData);

        return response()->json($impact, 201);
    }

    // Afficher un impact spécifique
    public function show($id)
    {
        $impact = Impact::with(['programme', 'site', 'activitePtba'])->findOrFail($id);
        return response()->json($impact);
    }

    // Mettre à jour un impact
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'programme_id' => 'sometimes|required|exists:programmes,id',
            'site_id' => 'sometimes|required|exists:sites,id',
            'activite_ptba_id' => 'sometimes|required|exists:activites_ptba,id',
        ]);

        $impact = Impact::findOrFail($id);
        $impact->update($validatedData);

        return response()->json($impact);
    }

    // Supprimer un impact
    public function destroy($id)
    {
        $impact = Impact::findOrFail($id);
        $impact->delete();

        return response()->json(['message' => 'Impact supprimé avec succès.']);
    }
}
