<?php

namespace App\Http\Controllers;

use App\Models\activiteBudgetAnnuel;
use App\Models\objectif;
use App\Models\organisation;
use App\Models\programme;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function users () {
        $user = User::all();
        return response()->json($user);
    }

    public function usersProgramme ($id){
        $programme = programme::with('users.organisations')->find($id);
        return response()->json($programme->users);
    }

    public function insertContributeurs (Request $request) {

        $programme = programme::find($request->programme_id);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email
        ]);
        
        if ($request->hasFile('photo')) {
            // Récupération du fichier uploadé
            $image = $request->file('photo');
            
            // Génération d'un nom unique pour l'image
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            
            // Déplacement du fichier vers le répertoire public/assets/images
            $destinationPath = public_path('/assets/images/');
            $image->move($destinationPath, $imageName);

            // Enregistrement du chemin de l'image (URL) dans la base de données
            $user->photo_url = asset('/assets/images/' . $imageName);
        }

        $user->save();
        $user->programmes()->attach($programme->id, ['role' => $request->role]);

        $organisation = organisation::where('libelle',$request->organisations)->first();

        if($organisation){
            
            $user->organisations()->attach($organisation->id, ['poste' => $request->poste]);
            return response()->json($user);
        }else{

            $organisation = organisation::create([
                'libelle' => $request->organisations,
            ]);
            
            $user->organisations()->attach($organisation->id, ['poste' => $request->poste]);
            return response()->json($user);
        }
    }

    public function userOrganisation(Request $request){
        
        $request->validate([
            'org' => 'required',
            'poste' => 'required',
            'user_id' => 'required'
        ]);

        $org = organisation::where('libelle', $request->org)->first();
        $org->users()->attach($request->user_id, ['poste' => $request->poste]);
    }

    public function userActivitebudgetannuel ($id) {
        $user = activiteBudgetAnnuel::with('users')->find($id);
        return response()->json($user->users);
    }

    public function affectations ($id) {
        $obejctif = User::with('objectifs', )->find($id);
        $programme = User::with('programmes')->find($id);
        $activitebudgetannuel = User::with('activiteBudgetAnnuels')->find($id);

        return response()->json([
            'objectifs' => $obejctif->objectifs,
            'programme'=> $programme->programmes,
            'activite'=> $activitebudgetannuel->activiteBudgetAnnuels,
        ]);
    }

    public function taches ($id) {
        $jalon = User::with('activites')->find($id);
        $livrable = User::with('livrables')->find($id);
        $ano = User::with('anos')->find($id);

        return response()->json([
            'ano' => $ano->anos,
            'jalon'=> $jalon->activites,
            'livrable'=> $livrable->livrables,
        ]);
    }
}
