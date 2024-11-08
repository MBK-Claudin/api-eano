<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Site; // Utilisez le modèle Site

class KoboController extends Controller
{
    public function importData(Request $request)
    {
        // URL de l'API Kobotoolbox
        $koboUrl = 'https://kc.kobotoolbox.org/api/v1/data/2328175?format=json';
        $apiKey = env('KOBO_API_KEY'); // Assurez-vous d'avoir configuré le token dans .env

        // Récupérer les données de Kobotoolbox
        $response = Http::withToken($apiKey)->get($koboUrl);

        if ($response->successful()) {
            $data = $response->json()['results'];

            foreach ($data as $item) {
                // Insérer uniquement certains champs dans la table `sites`
                Site::create([
                    'id' => $item['id'],
                    'libelle' => $item['libelle'],
                    'province' => $item['province'],
                    'departement' => $item['departement'],
                    'ville' => $item['ville'],
                    'coordonees_gps' => $item['coordonees_gps'],
                    'commentaire' => $item['commentaire'],
                ]);
            }

            return response()->json(['message' => 'Données importées avec succès dans la table sites !']);
        } else {
            return response()->json(['message' => 'Erreur lors de la récupération des données.'], 500);
        }
    }
}
