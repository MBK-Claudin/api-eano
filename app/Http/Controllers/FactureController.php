<?php

namespace App\Http\Controllers;

use App\Models\documentFacture;
use App\Models\facture;
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
        ]);

        //dd($request->hasFile('documents'));

        $facture = facture::create([
            'reference_facture' => $request->ref_facture,
            'type_facture' => $request->type_facture,
            'montant' => $request->montant,
            'date_reception' => $request->date_reception,
            'couverture' => $request->couverture,
        ]);
        //dd($facture);

        if($request->input('ano_id')){
            $facture->ano_id = $request->ano_id;
            $facture->save();
        }

        if($request->input('contract_id')){
            $facture->contract_id = $request->contract_id;
            $facture->save();
        }

        $titres = $request->input('titres');

        if ($request->hasFile('documents')) {
            //return response()->json($documents);
            foreach($request->file('documents') as $index => $file){
                $filePath = $file->store('documents', 'local');

                $titre = $titres[$index];

                // Récupérer le chemin d'accès au fichier
                $fileUrl = Storage::url($filePath);
    
                // Enregistrer les informations du document en base de données
                $document = new documentFacture();
                $document->titre = $titre;
                $document->file_name = $file->getClientOriginalName();
                $document->file_path = $filePath;
                $document->file_url = $fileUrl;
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
}
