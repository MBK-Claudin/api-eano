<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\contract;
use App\Models\documentContract;
use App\Models\programme;
use App\Models\activiteBudgetAnnuel;

use Illuminate\Support\Facades\Storage;
class ContractController extends Controller
{
    // Méthode pour insérer un contrat (existant dans votre code)
    public function insertContract(Request $request)
    {
        try {
            // Vérification des données reçues
            if (!$request->has(['ref_contract', 'libelle', 'description', 'montant', 'programme_id', 'activite_id'])) {
                return response()->json([
                    'message' => 'Données manquantes',
                    'errors' => [
                        'ref_contract' => $request->ref_contract ?? 'Non fourni',
                        'libelle' => $request->libelle ?? 'Non fourni',
                        'description' => $request->description ?? 'Non fourni',
                        'montant' => $request->montant ?? 'Non fourni',
                        'programme_id' => $request->programme_id ?? 'Non fourni',
                        'activite_id' => $request->activite_id ?? 'Non fourni',
                    ]
                ], 400);
            }

            // Vérifier si le programme et l’activité existent
            $programme = programme::find($request->programme_id);
            $activite = activiteBudgetAnnuel::find($request->activite_id);

            if (!$programme) {
                return response()->json([
                    'message' => 'Programme inexistant',
                    'programme_id' => $request->programme_id
                ], 400);
            }

            if (!$activite) {
                return response()->json([
                    'message' => 'Activité budgétaire inexistante',
                    'activite_id' => $request->activite_id
                ], 400);
            }

            // Création du contrat
            $contract = contract::create([
                'reference_contract' => $request->ref_contract,
                'libelle' => $request->libelle,
                'description' => $request->description,
                'montant' => $request->montant,
                'programme_id' => $request->programme_id
            ]);

            $contract->activite_budget_annuel_id = $request->activite_id;
            $contract->save();

            // Vérification des documents
            if ($request->hasFile('documents')) {
                $titres = $request->input('titres', []);

                foreach ($request->file('documents') as $index => $file) {
                    if (!$file->isValid()) {
                        return response()->json([
                            'message' => 'Fichier invalide',
                            'file_index' => $index,
                            'error' => $file->getErrorMessage()
                        ], 400);
                    }

                    $destinationPath = 'assets/documents';
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->move(public_path($destinationPath), $filename);
                    $fileUrl = asset('https://cgpgabon24.alwaysdata.net/api-eano/public/assets/documents/' . $filename);

                    $titre = $titres[$index] ?? 'Sans titre';

                    documentContract::create([
                        'titre' => $titre,
                        'file_name' => $filename,
                        'file_path' => $destinationPath . '/' . $filename,
                        'file_url' => $fileUrl,
                        'contract_id' => $contract->id
                    ]);
                }
            }

            return response()->json([
                'message' => 'Contract enregistré avec succès !',
                'contract_id' => $contract->id
            ], 201);
        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Erreur de base de données',
                'error' => $e->getMessage()
            ], 500);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Erreur inattendue',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // Méthode pour afficher les contrats liés à un programme
    public function contracts($id)
    {
        $contracts = Contract::with('documentContrats')->where('programme_id', $id)->get();

        if ($contracts->isEmpty()) {
            return response()->json(['message' => 'Aucun contrat trouvé pour ce programme'], 404);
        }

        return response()->json(['contracts' => $contracts], 200);
    }


    // Méthode pour obtenir tous les contrats
    public function getContracts()
    {
        $contracts = contract::with('documentContrats')->get();
        return response()->json([
            'contracts' => $contracts
        ]);
    }

    // Méthode pour obtenir les contrats d'un programme
    public function contractProgramme($id)
    {
        $programme = programme::with('budgetannuels.composants.souscomposants.activitesbudgetannuel.contracts')->find($id);

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
        $contract = contract::find($id);

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
        $contract = contract::find($id);

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
