<?php

namespace App\Http\Controllers;

use App\Models\ano;
use App\Models\contract;
use App\Models\documentFacture;
use App\Models\facture;
use App\Models\service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FactureController extends Controller
{
    public function factures( ) {
        $factures = facture::with('ano', 'contract')->get();
        return response()->json($factures);
    }

    public function insertFacture(Request $request) {

        //dd($request->hasFile('documents'));

        $request->validate([            
            'documents' => 'required|',
            'documents.*' => 'file|mimes:pdf|max:10240',
            'titres' => 'required',
            'ref_facture' => 'required',
            'type_facture' => 'required',
            'date_reception' => 'required',
            'montant' => 'required',
            'couverture' => 'required',
            'user_id' => 'required',
        ]);

        $service = service::find(1);

        $facture = facture::create([
            'reference_facture' => $request->ref_facture,
            'type_facture' => $request->type_facture,
            'montant' => $request->montant,
            'date_reception' => $request->date_reception,
            'couverture' => $request->couverture,
        ]);

        $facture->services()->attach($service->id, [
            'etape' => 'Reçu',
            'user_id' => $request->user_id,
        ]);

        if($request->input('ano')){
            $facture->ano_id = $request->ano;
            $facture->save();
        }

        if($request->input('contract')){
            $facture->contract_id = $request->contract;
            $facture->save();
        }

        $titres = $request->input('titres');

        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                $destinationPath = 'assets/documents';  // Chemin dans le répertoire public

                // Sauvegarder le fichier dans le répertoire public/assets/documents avec le nom original
                $filePath = $file->move(public_path($destinationPath), $file->getClientOriginalName());
    
                // Récupérer le nom du fichier
                $filename = $file->getClientOriginalName();
                
                // Utiliser la fonction asset() pour générer l'URL publique du fichier
                $fileUrl = asset('assets/documents/' . $filename);
    
                // Enregistrer les informations du document dans la base de données
                $document = new documentFacture();
                $document->titre = $titres[$index];
                $document->file_name = $filename;
                $document->file_path = $destinationPath . '/' . $filename;
                $document->file_url = $fileUrl;  // URL générée avec asset()
                $document->facture_id = $facture->id;
                $document->save();
            }

            return response()->json([
                'message' => 'facture enregistrer !'
            ], 200);
        }

        return response()->json([
            'message' => 'facture non enregistrer !'
        ], 400);

    }

    public function selectFacture($id){
        $facture = facture::with('documents', 'ano.evenements', 'contract')->find($id);
        return response()->json($facture);
    }

    public function anos () {
        $ano = ano::all();
        return response()->json($ano);
    }

    public function contracts () {
        $contract = contract::all();
        return response()->json($contract);
    }

    public function etatActuel ($id) {
        // $facture = facture::with('services')->find($id);
        // return response()->json($facture);


        // Recherche de la facture avec son suivi le plus récent
        $facture = Facture::with(['services' => function ($query) {
            $query->orderBy('created_at', 'desc')->first();
        }])->find($id);

        // Vérifie s'il existe un suivi associé
        if ($facture && $facture->services->isNotEmpty()) {
            $suiviActuel = $facture->services->first(); // Récupère la dernière étape
            return response()->json([
                'service' => $suiviActuel,
                'etape' => $suiviActuel->pivot->etape,
                'user_id' => $suiviActuel->pivot->user_id,
                'created_at' => $suiviActuel->pivot->created_at,
            ]);
        }

        return response()->json(['message' => 'Aucun suivi trouvé pour cette facture.'], 400);
    }


}
