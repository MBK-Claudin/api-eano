<?php

namespace App\Http\Controllers;

use App\Models\activite;
use App\Models\activiteBudgetAnnuel;
use App\Models\phase;
use App\Models\site;
use App\Models\User;
use Illuminate\Http\Request;

class ActiviteController extends Controller
{
    public function insertActivite (Request $request) {

        $request->validate([
            'activite_id' => 'required',
            'site' => 'required',
            'phase' => 'required',
            'libelle' => 'required',
            'date_debut' => 'required',
            'date_fin' => 'required',
            'responsable' => 'required',
            'email' => 'required',
            'budget' => 'required'
        ]);

        $activites = activite::create([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'budget' => $request->budget,
        ]);

        $activites->activite_budget_annuel_id = $request->activite_id;
        $activites->phase_id = $request->phase;
        $activites->save();

        $site = site::where('id', $request->site)->first();

        $activites->sites()->attach($site->id);

        $user = User::where('email', $request->email)->first();
        $user->activites()->attach($activites->id, ['role' => 'Responsable']);


        return response()->json([
            "message" => "création de l'activité ok !"
        ],);

    }

    public function getPhase (){
        $periodes = phase::all();
        return response()->json($periodes);
    }

    public function getSites () {
        $sites = site::all();
        return response()->json($sites);
    }

    public function getJalon (){
        $a = activite::all();
        return response()->json($a);
    }


    public function jalonActivite ($id) {
        $activite = activiteBudgetAnnuel::with('activites')->find($id);
        return response()->json($activite->activites);
    }

}
