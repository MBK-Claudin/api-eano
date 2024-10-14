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
        $facture = facture::with('documents', 'ano', 'contract')->find($id);
        return response()->json($facture);
    }
}
