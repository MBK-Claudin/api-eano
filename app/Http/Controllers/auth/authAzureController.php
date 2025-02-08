<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class authAzureController extends Controller
{
    public function login(){

        $scopes = [
            'User.Read',
            'openid', 'profile',
            'email',
            'offline_access',
        ];
        //dd( Socialite::with('azure')->scopes($scopes)->redirect());

        return Socialite::with('azure')->scopes($scopes)->redirect();
    }

    public function callback(){

        $azureUser = Socialite::with('azure')->stateless()->user();
        $photo = $azureUser->avatar ?? asset('assets/images/profile/default_user.png');

        $user = User::updateOrCreate([
            'azure_id' => $azureUser->id
        ],[
            'azure_id' => $azureUser->id,
            'photo_url' => $photo,
            'name' => $azureUser->name,
            'email' => $azureUser->email,
            'azure_token' => $azureUser->token,
            'azure_refresh_token' => $azureUser->refreshToken
        ]);

        Auth::login($user);
        $firstToken = $user->createToken('access-token', [
            'azure_id' => $user->azure_id,
            'email' => $user->email,
            'name' => $user->name,
        ]);

        $token = $firstToken->accessToken;
        return redirect('http://localhost:4200/login/check?token='. urlencode($token) . '&auth=' . urlencode($user->azure_id));

    }
    
    public function logout (Request $request) {
        Auth::logout();
        $request->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function authUser ($id){
        $user = User::where('azure_id', $id)->first();
        return response()->json(['user' => $user]);
    }

}
