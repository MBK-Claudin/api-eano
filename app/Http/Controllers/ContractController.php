<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\contract;
use App\Models\documentContract;
use App\Models\programme;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function insertContract (Request $request) {

        $request->validate([
            'documents' => 'required|',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'titres' => 'required',
            'ref_contract' => 'required',
            'libelle' => 'required',
            'description' => 'required',
            'montant' => 'required',
            'activite_id' => 'required',
        ]);

        $contract = contract::create([
            'reference_contract' => $request->ref_contract,
            'libelle' =>  $request->libelle,
            'description' => $request->description,
            'montant' => $request->montant,
        ]);

        $contract->activite_budget_annuel_id = $request->activite_id;
        $contract->save();

        $titres = $request->input('titres');
        

        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                // Récupérer le chemin d'accès au fichier
                $fileUrl = Storage::url($filePath);
    
                // Enregistrer les informations du document en base de données
                $document = new documentContract();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
                $document->contract_id = $contract->id;
                $document->save();
            }

            return response()->json([
                'message' => 'contract enregistrer !'
            ], 200);
        }

        return response()->json([
            'message' => 'contract non enregistrer !'
        ], 400);
    }

    public function contracts($id) {
        $contracts = programme::with('contracts')->find($id);
        return response()->json($contracts);
    }
    
    public function getContracts (){
        $contracts = contract::all();
        return response()->json([
            'contracts' => $contracts
        ]);
    }

    public function contractProgramme ($id) {
        $contracts = Programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.contracts')
        ->find($id)
        ->budgetannuels
        ->flatMap(function($budgetannuels) {
            return $budgetannuels->composants->flatMap(function($composants) {
                return $composants->souscomposants->flatMap(function($souscomposants) {
                    return $souscomposants->activitesbudgetannuel->flatMap(function($activitesbudgetannuel){
                        return $activitesbudgetannuel->contracts;
                    });
                });
            });
        });

        return response()->json($contracts);
    }

}
