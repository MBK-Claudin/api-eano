<?php

namespace App\Http\Controllers;

use App\Models\Financement;
use Illuminate\Http\Request;
use App\Models\Programme;
use App\Models\Organisation;
use App\Models\BudgetAnnuel;

class FinancementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($programmeId)
    {
        $financements = Financement::with(['programme', 'organisation'])
            ->where('programme_id', $programmeId)
            ->get();

        if ($financements->isEmpty()) {
            return response()->json(['message' => 'Aucun financement trouvé pour ce programme'], 404);
        }

        $data = $financements->map(function ($financement) {
            return [
                'programme_id' => $financement->programme_id,
                'id' => $financement->id,
                'type_financement' => $financement->type_financement,
                'montant' => $financement->montant,
                'partenaire' => $financement->organisation ? $financement->organisation->libelle : null,
                'montant_usd' => $financement->montant_usd,
                'statut' => $financement->statut,
            ];
        });

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Étape 1 : Valider les données du formulaire
        $validatedData = $request->validate([
            'type_financement' => 'required|string|max:255',
            'montant' => 'required|numeric',
            'montant_usd' => 'nullable|numeric',
            'organisation_id' => 'required|integer|exists:organisations,id',
            'statut' => 'required|string|max:50',
            'programme_id' => 'required|integer|exists:programmes,id',
        ]);

        // Étape 2 : Créer un nouvel enregistrement de financement
        $financement = Financement::create([
            'type_financement' => $validatedData['type_financement'],
            'montant' => $validatedData['montant'],
            'montant_usd' => $validatedData['montant_usd'] ?? null,
            'organisation_id' => $validatedData['organisation_id'],
            'statut' => $validatedData['statut'],
            'programme_id' => $validatedData['programme_id'],
        ]);

        // Étape 3 : Retourner la réponse JSON
        return response()->json([
            'message' => 'Financement créé avec succès',
            'data' => $financement
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $financement = Financement::with(['programme', 'organisation'])->find($id);

        if (!$financement) {
            return response()->json(['message' => 'Financement non trouvé'], 404);
        }

        return response()->json([
            'id' => $financement->id,
            'type_financement' => $financement->type_financement,
            'montant' => $financement->montant,
            'partenaire' => $financement->organisation ? $financement->organisation->libelle : null,
            'montant_usd' => $financement->montant_usd,
            'statut' => $financement->statut,
            'principale' => $financement->principale,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $financement = Financement::find($id);

        if (!$financement) {
            return response()->json(['message' => 'Financement non trouvé'], 404);
        }

        $financement->update([
            'type_financement' => $request->type_financement,
            'montant' => $request->montant,
            'partenaire' => $request->partenaire,
            'montant_usd' => $request->montant_usd,
            'statut' => $request->statut,
            'programme_id' => $request->programme_id,
        ]);

        return response()->json([
            'message' => 'Financement mis à jour avec succès',
            'data' => $financement
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $financement = Financement::find($id);

        if (!$financement) {
            return response()->json(['message' => 'Financement non trouvé'], 404);
        }

        $financement->delete();

        return response()->json([
            'message' => 'Financement supprimé avec succès'
        ], 200);
    }
}
