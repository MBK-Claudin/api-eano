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
        $factures = facture::with('ano', 'contract','documents')->get();
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
                foreach ($request->file('documents') as $index => $file) {
                    $destinationPath = 'assets/documents';
                    $filename = uniqid() . '_' . $file->getClientOriginalName();
                    $filePath = $file->move(public_path($destinationPath), $filename);
                    $fileUrl = asset('https://cgpgabon24.alwaysdata.net/api-eano/public/assets/documents/' . $filename);

                    $titre = $titres[$index];

                    $document =new documentFacture();
                    $document->titre = $titre;
                    $document->file_name =  $filename;
                    $document->file_path =  $destinationPath . '/'. $filename;
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

    public function etatActuel($id){
        $facture = Facture::find($id);

        if (!$facture) {
            return response()->json(['message' => 'Facture introuvable.'], 404);
        }

        $suiviActuel = $facture->services()->orderBy('pivot_created_at', 'desc')->first();

        if ($suiviActuel) {
            return response()->json([
                'service' => $suiviActuel,
                'etape' => $suiviActuel->pivot->etape,
                'user_id' => $suiviActuel->pivot->user_id,
                'created_at' => $suiviActuel->pivot->created_at,
            ]);
        }

        return response()->json(['message' => 'Aucun suivi trouvé pour cette facture.'], 400);
    }


    public function traitementFacture ($idFacture, $idService, $user_id){
        $facture = facture::find($idFacture);

        $facture->services()->attach($idService, [
            'etape' => 'Traitement',
            'user_id' => $user_id,
        ]);

        return response()->json([
            'message' => 'Facture traitée avec succès',
        ]);
    }
}
