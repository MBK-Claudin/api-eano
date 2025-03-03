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
            'libelle' => $request->libelle,
            'province' => $request->province,
            'departement' => $request->departement,
            'ville' => $request->ville,
            'coordonnees_gps' => $request->coordonnees_gps,
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




}
