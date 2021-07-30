<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    //
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleProviderCallback()
    {
        // stateless 禁用会话状态认证
        $oauthUser = Socialite::driver('github')->stateless()->user();
        $user = User::where('email', $oauthUser->email)->first();

        $data = [
            'name' => $oauthUser->getNickname(),
            //'avatar'   => $oauthUser->getAvatar(),
            'email' => $oauthUser->getEmail(),
            'password' => Hash::make('alex'),
        ];
        if(!$user){
            User::create($data);
        }
        Auth::login($user);
        return redirect()->guest('/');
    }
}
