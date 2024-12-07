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
                'partenaire' => $financement->partenaire,
                'montant' => $financement->montant,
                'principale' => $financement->principale,
                'budgetAnnuel' => $financement->budgetAnnuel ? $financement->budgetAnnuel->libelle : null,
                'organisation' => $financement->organisation ? $financement->organisation->libelle : null,
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Financement $financement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Financement $financement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Financement $financement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Financement $financement)
    {
        //
    }
}
