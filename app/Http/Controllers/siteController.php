<?php

namespace App\Http\Controllers;

use App\Models\site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class siteController extends Controller
{
    public function sites(){
        $site = site::all();
        return response()->json($site);
    }

    public function insertSite(Request $request){
        $site = site::create([
            'libelle' => $request->site,
            'province' => $request->province,
            'departement' => $request->departement,
            'ville' => $request->ville,
            'coordonnees_gps' => $request->coordonnee,
            'commentaire' => $request->commentaire
        ]);

        return response()->json($site);
    }

    public function deleteSite($id){
        $site = site::find($id);
        $site->delete();
        return response()->json($site);
    }

    public function editSite(Request $request){
        $site = site::find($request->id);
        $site->libelle = $request->site;
        $site->province = $request->province;
        $site->departement = $request->departement;
        $site->ville = $request->ville;
        $site->coordonnees_gps = $request->coordonnee;
        $site->commentaire = $request->commentaire;
        $site->save();
        return response()->json($site);
    }

//     public function storeFromKoboData()
//     {
//         // URL de l'API Kobotoolbox
//         $url = 'https://kc.kobotoolbox.org/api/v1/data?format=json';

//         // Récupérer les données de Kobotoolbox avec autorisation
//         $response = Http::withHeaders([
//             'Authorization' => 'e60dcc1af1c6236909e0a5957b2d4f665e6fa872'
//         ])->get($url);
// dd($response);
//         // Vérifier si la requête a réussi
//         if ($response->failed()) {
//             return response()->json(['error' => 'Erreur lors de la récupération des données de Kobotoolbox'], 500);
//         }

//         $data = $response->json();

//         // Parcourir les données et les insérer dans la table sites
//         foreach ($data as $item) {
//             // Extraire les champs nécessaires
//             $siteData = [
//                 'id' => $item['id'] ?? null,
//                 'libelle' => $item['libelle'] ?? null,
//                 'province' => $item['province'] ?? null,
//                 'departement' => $item['departement'] ?? null,
//                 'ville' => $item['ville'] ?? null,
//                 'coordonnees_gps' => $item['coordonees_gps'] ?? null,
//                 'commentaire' => $item['commentaire'] ?? null, // Gestion du champ nullable
//             ];

//             // Valider les données avant de les insérer
//             $validator = Validator::make($siteData, [
//                 'id'=> 'required|string',
//                 'libelle' => 'required|string',
//                 'province' => 'required|string',
//                 'departement' => 'required|string',
//                 'ville' => 'required|string',
//                 'coordonnees_gps' => 'required|string',
//                 'commentaire' => 'nullable|string',
//             ]);

//             if ($validator->fails()) {
//                 return response()->json(['errors' => $validator->errors()], 400);
//             }

//             // Insérer les données dans la table sites
//             Site::create($siteData);
//         }

//         return response()->json(['message' => 'Sites ajoutés avec succès'], 201);
//     }




}
