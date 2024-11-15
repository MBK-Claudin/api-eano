<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    // Afficher toutes les missions
    public function index()
    {
        $missions = Mission::with(['activite', 'users', 'sites', 'livrables'])->get();
        return response()->json($missions);
    }

    // Afficher une mission spécifique
    public function show($id)
    {
        $mission = Mission::with(['activite', 'users', 'sites', 'livrables'])->find($id);

        if (!$mission) {
            return response()->json(['message' => 'Mission not found'], 404);
        }

        return response()->json($mission);
    }

    // Créer une nouvelle mission
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'libelle' => 'required|string|max:255',
            'description' => 'required|string',
            'objectif' => 'required|string',
            'activite_ids' => 'array',
            'user_ids' => 'array',
            'user_roles' => 'array',
            'site_ids' => 'array',
            'livrable_ids' => 'array',
        ]);

        $mission = Mission::create($validatedData);

        // Attacher les activités
        if ($request->has('activite_ids')) {
            $mission->activite()->sync($request->activite_ids);
        }

        // Attacher les utilisateurs avec des rôles
        if ($request->has('user_ids') && $request->has('user_roles')) {
            $usersWithRoles = array_combine($request->user_ids, $request->user_roles);
            $mission->users()->sync($usersWithRoles);
        }

        // Attacher les sites
        if ($request->has('site_ids')) {
            $mission->sites()->sync($request->site_ids);
        }

        // Attacher les livrables
        if ($request->has('livrable_ids')) {
            $mission->livrables()->sync($request->livrable_ids);
        }

        return response()->json($mission, 201);
    }

    // Mettre à jour une mission
    public function update(Request $request, $id)
    {
        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json(['message' => 'Mission not found'], 404);
        }

        $validatedData = $request->validate([
            'libelle' => 'string|max:255',
            'description' => 'string',
            'objectif' => 'string',
            'activite_ids' => 'array',
            'user_ids' => 'array',
            'user_roles' => 'array',
            'site_ids' => 'array',
            'livrable_ids' => 'array',
        ]);

        $mission->update($validatedData);

        // Mettre à jour les activités
        if ($request->has('activite_ids')) {
            $mission->activite()->sync($request->activite_ids);
        }

        // Mettre à jour les utilisateurs avec des rôles
        if ($request->has('user_ids') && $request->has('user_roles')) {
            $usersWithRoles = array_combine($request->user_ids, $request->user_roles);
            $mission->users()->sync($usersWithRoles);
        }

        // Mettre à jour les sites
        if ($request->has('site_ids')) {
            $mission->sites()->sync($request->site_ids);
        }

        // Mettre à jour les livrables
        if ($request->has('livrable_ids')) {
            $mission->livrables()->sync($request->livrable_ids);
        }

        return response()->json($mission);
    }

    // Supprimer une mission
    public function destroy($id)
    {
        $mission = Mission::find($id);

        if (!$mission) {
            return response()->json(['message' => 'Mission not found'], 404);
        }

        $mission->activite()->detach();
        $mission->users()->detach();
        $mission->sites()->detach();
        $mission->livrables()->detach();

        $mission->delete();

        return response()->json(['message' => 'Mission deleted successfully']);
    }
}
