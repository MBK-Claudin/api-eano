<?php

namespace App\Http\Controllers;

use App\Models\organisation;
use Illuminate\Http\Request;

class OrganisationCOntroller extends Controller
{
    public function organisations () {
        $org = organisation::get();
        if ($org) {
            return response()->json($org);
        }else {
            return response()->json(['message' => 'No organisation found'], 404);
        }
    }

    public function deleteOrganisations ($id) {
        $org = organisation::find($id);
        $org->delete();
        return response()->json([
            'message' => "organisation supprimer !!!!!!!"
        ]);
    }
    public function participeOrganisations () {
        $org = organisation::with('programmes', 'objectifs');
        return response()->json($org);

    }

    public function insertOrganisations (Request $request){
        $org = organisation::create([
            'libelle' => $request->libelle
        ]);

        return response()->json($org);
    }
}
