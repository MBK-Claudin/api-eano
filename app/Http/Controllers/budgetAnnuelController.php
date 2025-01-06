<?php

namespace App\Http\Controllers;

use App\Imports\budgetAnnuelImport;
use App\Models\activiteBudgetAnnuel;
use App\Models\budgetAnnuel;
use App\Models\programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class budgetAnnuelController extends Controller
{
    public function insertBudgetAnnuel(Request $request){

        $request->validate([
            'periode' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'excel' => 'required',
        ]);

        $document = $request->file('excel');
        $filePath = $document->store('documents', 'local');
        $fileUrl = Storage::url($filePath);

        $ptba = budgetAnnuel::create([
            'periode' => $request->periode,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'file_name' => $document->getClientOriginalName(),
            'file_path' => $filePath,
            'file_url' => $fileUrl,
        ]);

        $ptba->programme_id = $request->programme_id;
        $ptba->save();

        Excel::import(new budgetAnnuelImport($ptba->id, $request->programme_id), $request->file('excel'));

        return response()->json([
            'message' => 'Budget Annuel inserted successfully',
        ], 201);
    }

    public function detailBudgetAnnuel ($id){
        $budget = budgetAnnuel::with('composants.souscomposants.activitesbudgetannuel')->find($id);
        $programme = budgetAnnuel::with('programme')->find($id);
        return response()->json([
            'budget' => $budget,
            'programme' => $programme->programme,
        ]);
    }

    public function budgetannuels ($id){
        $programme = programme::find($id);
        return response()->json($programme->budgetannuels);
    }

    public function activites () {
        $activites = activiteBudgetAnnuel::all();
        return response()->json($activites);
    }

    public function activite ($id) {
        $activites = activiteBudgetAnnuel::with( 'users')->find($id);
        return response()->json($activites);
    }

    public function deleteActivite ($id) {
        $activite = activiteBudgetAnnuel::find($id);
        $activite->delete();
        return response()->json([
            'messge' => "activité du budget annuel supprimer !!!!!"
        ]);
    }

    public function allBudget () {
        $budgets = budgetAnnuel::with('programme')->get();
        return response()->json($budgets);
    }


    public function deleteBudget($id)
{
    // Récupérer le budget annuel
    $budget = budgetAnnuel::find($id);

    if (!$budget) {
        return response()->json(['message' => 'Budget not found'], 404);
    }

    // Supprimer le fichier Excel associé si présent
    if ($budget->file_path && Storage::exists($budget->file_path)) {
        Storage::delete($budget->file_path);
    }

    // Supprimer le budget de la base de données
    $budget->delete();

    return response()->json([
        'message' => 'Budget Annuel deleted successfully',
        'budget_id' => $id,
    ], 200);
}

}
