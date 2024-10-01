<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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

        return Socialite::with('azure')->scopes($scopes)->redirect();
    }
    
    public function callback(){
        $azureUser = Socialite::with('azure')->stateless()->user();
        $photo = $azureUser->avatar ?? asset('assets/images/profile/default_user.png');

        $user = User::updateOrCreate([
            'azure_id' => $azureUser->id,
        ],[
            'azure_id' => $azureUser->id,
            'photo_url' => $photo,
            'name' => $azureUser->name,
            'email' => $azureUser->email,
            'azure_token' => $azureUser->token,
            'azure_refresh_token' => $azureUser->refreshToken
        ]);

        $token = $user->createTonken('access-token')->plainTextToken;

        return redirect(''. $token);
    }
}
