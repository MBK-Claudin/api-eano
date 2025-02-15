<?php

namespace App\Http\Controllers;

use App\Models\mission;
use App\Models\enquete;

use Illuminate\Http\Request;

class MissionController extends Controller
{
    // Afficher toutes les missions
    public function getMission($programmeId)
    {
        $missions = Mission::with(['programme','activite', 'user', 'site'])
        ->where('programme_id', $programmeId)
        ->get();

        if (!$missions) {
            return response()->json(['message' => 'mission non trouvé'], 404);
        }

        $missionData = $missions->map(function ($mission) {
            return [
            'programme_id' => $mission->programme_id,
            'id' => $mission->id,
            'libelle' => $mission->libelle,
            'description' => $mission->description,
            'objectif' => $mission->objectif,
            'date_debut' => $mission->date_debut,
            'statut' => $mission->statut,
            'site' => $mission->site_id ? $mission->site->libelle : null,
            'activite' => $mission->activite_id ? $mission->activite->libelle : null,
            'responsable' => $mission->user_id ? $mission->user->name : null,

        ];
    });
        return response()->json($missionData);
    }

    // Afficher une mission spécifique
    public function show($id)
    {
        $mission = Mission::with(['activite', 'user', 'site'])->find($id);

        if (!$mission) {
            return response()->json(['message' => 'Mission not found'], 404);
        }

        return response()->json($mission);
    }



    // Créer une nouvelle mission
    public function store(Request $request)
    {
        $mission = Mission::create([

            'libelle' => $request->libelle,
            'description' => $request->definition_objectif_specifique,
            'objectif' => $request->objectif,
            'date_debut' => $request->date_debut ,
            'statut' => $request->statut,
            'site_id' => $request->site,
            'programme_id' => $request->programme,
            'activite_id' => $request->activite,
            'user_id' => $request->user

        ]);

        return response()->json($mission, 201);
    }



    public function update(Request $request, $id)
    {
        // Valider les données reçues
        $validatedData = $request->validate([
            'libelle' => 'string|max:255',
            'description' => 'string',
            'objectif' => 'string',
            'activite_id' => 'string',
            'user_id' => 'string',
            'site_id' => 'string',
            'statut'=> 'string'
        ]);

        // Trouver l'impact à mettre à jour
        $impact = Impact::find($id);

        if (!$impact) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Mettre à jour les données de l'impact
        $impact->update($validatedData);

        // Retourner la réponse avec l'impact mis à jour
        return response()->json([
            'message' => 'Impact mis à jour avec succès',
            'data' => $impact,
        ]);
    }


    // Supprimer une mission
    public function destroy($id)
    {
        // Trouver l'impact à supprimer
        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json(['message' => 'Impact non trouvé'], 404);
        }

        // Supprimer l'impact
        $mission->delete();

        // Retourner une réponse indiquant que la suppression a réussi
        return response()->json(['message' => 'Impact supprimé avec succès'], 200);
    }

    public function enquete(Request $request)
    {
        // dd( $request->all());

        $missionId = $request->input('mission_id');
        $file = $request->file('csv_file');

        if (($handle = fopen($file, "r")) !== false) {
            $header = fgetcsv($handle, 1000, ","); // Lire l'en-tête du fichier CSV

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                // Déboguer les données pour vérifier leur contenu
                // dd($data);

                // Nettoyer les données avec mb_convert_encoding pour gérer l'encodage
                $intituleProjet = isset($data[2]) ? mb_convert_encoding($data[2], 'UTF-8', 'auto') : null;
                $ministere = isset($data[3]) ? mb_convert_encoding($data[3], 'UTF-8', 'auto') : null;
                $gabonProvince = isset($data[4]) ? mb_convert_encoding($data[4], 'UTF-8', 'auto') : null;
                $gabonDepartement = isset($data[5]) ? mb_convert_encoding($data[5], 'UTF-8', 'auto') : null;
                $gabonAdm3 = isset($data[6]) ? mb_convert_encoding($data[6], 'UTF-8', 'auto') : null;
                $observations = isset($data[7]) ? mb_convert_encoding($data[7], 'UTF-8', 'auto') : null;

                // Convertir latitude et longitude en nombres (float)
                $latitude = isset($data[9]) && is_numeric($data[9]) ? (float)$data[9] : null;
                $longitude = isset($data[10]) && is_numeric($data[10]) ? (float)$data[10] : null;

                // Assurez-vous que les dates sont au bon format
                $dateDebut = isset($data[14]) ? \DateTime::createFromFormat('d/m/Y', $data[14]) : null;
                $dateFin = isset($data[15]) ? \DateTime::createFromFormat('d/m/Y', $data[15]) : null;

                // Vérifier que le cout_initial est un nombre valide
                $coutInitial = isset($data[13]) && is_numeric($data[13]) ? (float)$data[13] : null;

                enquete::create([
                    'mission_id' => $missionId,
                    'intitule_projet' => $intituleProjet,
                    'ministere' => $ministere,
                    'gabon_province' => $gabonProvince,
                    'gabon_departement' => $gabonDepartement,
                    'gabon_adm3' => $gabonAdm3,
                    'observations' => $observations,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'altitude' => isset($data[11]) ? $data[11] : null, // Assurez-vous que l'altitude est correcte
                    'precision' => isset($data[12]) ? $data[12] : null,
                    'cout_initial' => $coutInitial,
                    'date_debut' => $dateDebut ? $dateDebut->format('Y-m-d') : null,
                    'date_fin' => $dateFin ? $dateFin->format('Y-m-d') : null,
                    'photo_url' => isset($data[55]) ? $data[55] : null,
                    'video_url' => isset($data[57]) ? $data[57] : null
                ]);
            }

            fclose($handle);
        }

        return response()->json(['message' => 'Données importées avec succès'], 200);
    }



}
