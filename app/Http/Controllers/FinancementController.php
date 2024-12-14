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
        $financements = Financement::with(['programme', 'organisation', 'budgetAnnuel'])
            ->where('programme_id', $programmeId)
            ->get();

        // Vérifie si des financements existent pour ce programme
        if ($financements->isEmpty()) {
            return response()->json(['message' => 'Aucun financement trouvé pour ce programme'], 404);
        }

        // Formater les données pour inclure les libellés des relations
        $data = $financements->map(function ($financement) {
            return [
                'programme_id' => $financement->programme_id,  // Juste l'ID du programme
                'id' => $financement->id,
                'type_financement' => $financement->type_financement,
                'montant' => $financement->montant,
                'principale' => $financement->principale,
                'budgetAnnuel' => $financement->budgetAnnuel ? $financement->budgetAnnuel->Budget_planifier_fcfa : null,
                'partenaire' => $financement->organisation ? $financement->organisation->libelle : null,
                'created_at' => $financement->created_at,
                'updated_at' => $financement->updated_at,
            ];
        });

        // Retourne les données en JSON
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Récupère directement les données depuis le request sans validation
        $financement = Financement::create([
            'programme_id' => $request->programme_id,
            'type_financement' => $request->type_financement,
            'montant' => $request->montant,
            'principale' => $request->principale,
            'budget_annuel_id' => $request->budget_annuel_id,
            //partenaire
            'organisation_id' => $request->organisation_id,
        ]);

        // Retourner la réponse avec les données du financement créé
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
        $financement = Financement::with(['programme', 'organisation', 'budgetAnnuel'])->find($id);

        if (!$financement) {
            return response()->json(['message' => 'Financement non trouvé'], 404);
        }

        // Retourne les détails du financement avec les libellés des relations
        return response()->json([
            'id' => $financement->id,
            'programme_id' => $financement->programme_id,
            'type_financement' => $financement->type_financement,
            'partenaire' => $financement->partenaire,
            'montant' => $financement->montant,
            'principale' => $financement->principale,
            'budgetAnnuel' => $financement->budgetAnnuel ? $financement->budgetAnnuel->Budget_planifier_fcfa : null,
            'organisation' => $financement->organisation ? $financement->organisation->libelle : null,
            'created_at' => $financement->created_at,
            'updated_at' => $financement->updated_at,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
   /**
 * Show the form for editing the specified resource.
 */
public function edit($id)
{
    // Récupère le financement avec ses relations
    $financement = Financement::with(['programme', 'organisation', 'budgetAnnuel'])->find($id);

    if (!$financement) {
        return response()->json(['message' => 'Financement non trouvé'], 404);
    }

    // Retourne les détails du financement avec les libellés des relations pour édition
    return response()->json([
        'id' => $financement->id,
        'programme_id' => $financement->programme_id,
        'type_financement' => $financement->type_financement,
        'partenaire' => $financement->partenaire,
        'montant' => $financement->montant,
        'principale' => $financement->principale,
        'budgetAnnuel' => $financement->budgetAnnuel ? $financement->budgetAnnuel->Budget_planifier_fcfa: null,
        'organisation' => $financement->organisation ? $financement->organisation->libelle : null,
        'created_at' => $financement->created_at,
        'updated_at' => $financement->updated_at,
    ]);
}


    /**
     * Update the specified resource in storage.
     */
    /**
 * Update the specified resource in storage.
 */
public function update(Request $request, $id)
{
    // Récupère le financement à mettre à jour
    $financement = Financement::find($id);

    if (!$financement) {
        return response()->json(['message' => 'Financement non trouvé'], 404);
    }

    // Met à jour les champs avec les nouvelles données
    $financement->programme_id = $request->programme_id;
    $financement->type_financement = $request->type_financement;
    $financement->partenaire = $request->partenaire;
    $financement->montant = $request->montant;
    $financement->principale = $request->principale;
    $financement->budget_annuel_id = $request->budget_annuel_id;
    $financement->organisation_id = $request->organisation_id;

    // Sauvegarde les modifications dans la base de données
    $financement->save();

    // Retourne une réponse indiquant que la mise à jour a été effectuée
    return response()->json([
        'message' => 'Financement mis à jour avec succès',
        'data' => $financement
    ], 200);
}


    /**
     * Remove the specified resource from storage.
     */
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
