<?php

namespace App\Http\Controllers;

use App\Models\activiteBudgetAnnuel;
use App\Models\objectif;
use App\Models\organisation;
use App\Models\programme;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class UserController extends Controller
{
    public function users () {
        $user= User::with('roles')->get();
        // $user = User::all();
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
            'email' => $request->email,
            'password' => Hash::make('CGP@2024'),

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




    public function updateContributeurs(Request $request, $id) {
        // Validation des données d'entrée
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, // Éviter les doublons pour l'email
            'programme_id' => 'required|exists:programmes,id',
            'organisations' => 'required|string',
            'role' => 'required|string',
            'poste' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation de l'image
        ]);

        // Trouver l'utilisateur par son ID
        $user = User::findOrFail($id);

        // Mise à jour des informations de l'utilisateur
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Gérer l'upload de la photo (si présente)
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
            $user->save(); // Sauvegarde après l'ajout de la photo
        }

        // Attacher l'utilisateur au programme avec son rôle
        $programme = programme::findOrFail($request->programme_id);
        $user->programmes()->syncWithoutDetaching([$programme->id => ['role' => $request->role]]);

        // Gestion de l'organisation
        $organisation = organisation::where('libelle', $request->organisations)->first();

        if ($organisation) {
            // Si l'organisation existe, on l'attache à l'utilisateur
            $user->organisations()->syncWithoutDetaching([$organisation->id => ['poste' => $request->poste]]);
        } else {
            // Si l'organisation n'existe pas, on la crée et on l'associe à l'utilisateur
            $organisation = organisation::create([
                'libelle' => $request->organisations,
            ]);

            // Attacher l'organisation à l'utilisateur
            $user->organisations()->syncWithoutDetaching([$organisation->id => ['poste' => $request->poste]]);
        }

        return response()->json($user, 200);
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



    public function logins(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 400);
        }

        // Vérifier les identifiants
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Email or password is incorrect'], 401);
        }

        // Récupérer l'utilisateur
        $user = auth()->user();

        // Vérifier si le token est déjà présent dans la colonne azure_token
        if (!$user->azure_id) {
            // Si le token n'existe pas, on l'enregistre
            $user->azure_id = $token;
            $user->save();
        }

        // Charger les rôles de l'utilisateur
        $userWithRoles = $user->load('roles');

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id'=> $userWithRoles->id,
                'photo_url' => $userWithRoles->photo_url,
                'name' => $userWithRoles->email,
                // 'role' => $userWithRoles->roles->pluck('nomrole'),
            ]
        ]);
    }






public function addOrCreateContributeurToProgramme(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'name' => 'required|string',
        'role' => 'required|string',
    ]);

    $programme = programme::find($request->programme_id);


    if (!$programme) {
        return response()->json(['message' => 'Programme not found'], 404);
    }

    $user = User::where('email', $request->email)->first();


    if (!$user) {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('CGP@2024'),

        ]);
    }


    if ($programme->users()->where('user_id', $user->id)->exists()) {
        return response()->json([
            'message' => 'User is already a contributor to this programme',
            'programme_id' => $programmeId,
            'user_id' => $user->id,
        ], 200);
    }

    $programme->users()->attach($user->id, [
        'role' => $request->role,
        'added_at' => now()
    ]);

    return response()->json([
        'message' => 'User successfully added as a contributor to the programme',
        'programme_id' => $programmeId,
        'user_id' => $user->id,
        'role' => $request->role,
    ], 201);
}






public function removeContributeurFromProgramme($programme_id, $user_id)
{
    // Récupérer le programme
    $programme = Programme::find($programme_id);

    if (!$programme) {
        return response()->json(['message' => 'Programme not found'], 404);
    }

    // Récupérer l'utilisateur
    $user = User::find($user_id);

    if (!$user) {
        return response()->json(['message' => 'User not found'], 404);
    }

    // Vérifier si l'utilisateur est lié au programme
    if (!$programme->users()->where('user_id', $user->id)->exists()) {
        return response()->json([
            'message' => 'User is not a contributor to this programme'
        ], 404);
    }

    // Supprimer la relation
    $programme->users()->detach($user->id);

    return response()->json([
        'message' => 'Contributor removed successfully from the programme',
        'programme_id' => $programme_id,
        'user_id' => $user_id,
    ], 200);
}



}
