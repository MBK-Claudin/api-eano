<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Contract;
use App\Models\DocumentContract;
use App\Models\Programme;
use Illuminate\Support\Facades\Storage;
class ContractController extends Controller
{
    // Méthode pour insérer un contrat (existant dans votre code)
    public function insertContract(Request $request)
    {
        $request->validate([
            'documents' => 'required|',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'titres' => 'required',
            'ref_contract' => 'required',
            'libelle' => 'required',
            'description' => 'required',
            'montant' => 'required',
            'activite_id' => 'required',
            'programme_id' => 'required',

        ]);

        $contract = Contract::create([
            'reference_contract' => $request->ref_contract,
            'libelle' => $request->libelle,
            'description' => $request->description,
            'montant' => $request->montant,
            'programme_id'=> $request->programme_id
        ]);

        $contract->activite_budget_annuel_id = $request->activite_id;
        $contract->save();

        $titres = $request->input('titres');

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                $fileUrl = Storage::url($filePath);
                $document = new DocumentContract();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
                $document->contract_id = $contract->id;
                $document->save();
            }

            return response()->json([
                'message' => 'Contract enregistré !'
            ], 200);
        }

        return response()->json([
            'message' => 'Contract non enregistré !'
        ], 400);
    }

    // Méthode pour afficher les contrats liés à un programme
    public function contracts($id)
    {
        $contracts = Programme::with('contracts')->find($id);
        return response()->json($contracts);
    }

    // Méthode pour obtenir tous les contrats
    public function getContracts()
    {
        $contracts = Contract::all();
        return response()->json([
            'contracts' => $contracts
        ]);
    }

    // Méthode pour obtenir les contrats d'un programme
    public function contractProgramme($id)
    {
        $programme = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.contracts')->find($id);

        if (!$programme) {
            return response()->json([
                'error' => 'Programme not found',
            ], 404);
        }

        $contracts = $programme->budgetannuels
            ->flatMap(function ($budgetannuels) {
                return $budgetannuels->composants->flatMap(function ($composants) {
                    return $composants->souscomposants->flatMap(function ($souscomposants) {
                        return $souscomposants->activitesbudgetannuel->flatMap(function ($activitesbudgetannuel) {
                            return $activitesbudgetannuel->contracts;
                        });
                    });
                });
            });

        return response()->json($contracts);
    }

    // Méthode pour mettre à jour un contrat
    public function updateContract(Request $request, $id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
                'error' => 'Contract not found',
            ], 404);
        }

        $contract->update($request->only(['reference_contract', 'libelle', 'description', 'montant']));

        // Mise à jour des documents (si présents dans la requête)
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $filePath = $file->store('documents', 'local');

                $titre = $request->titres[$index];
                $fileUrl = Storage::url($filePath);

                $document = new DocumentContract();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
                $document->contract_id = $contract->id;
                $document->save();
            }
        }

        return response()->json([
            'message' => 'Contract mis à jour avec succès',
            'contract' => $contract
        ]);
    }

    // Méthode pour supprimer un contrat
    public function deleteContract($id)
    {
        $contract = Contract::find($id);

        if (!$contract) {
            return response()->json([
                'error' => 'Contract not found',
            ], 404);
        }

        // Supprimer les documents associés
        foreach ($contract->documents as $document) {
            Storage::delete($document->file_path);
            $document->delete();
        }

        // Supprimer le contrat
        $contract->delete();

        return response()->json([
            'message' => 'Contract supprimé avec succès'
        ]);
    }
}
