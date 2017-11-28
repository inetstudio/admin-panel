<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->stateless()->scopes(['email'])->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $usersService = app()->make('UsersService');

        $providerObj = Socialite::driver($provider)->stateless();

        $authUser = $usersService->createOrGetSocialUser($providerObj, $provider);
        Auth::login($authUser, true);

        return redirect('/');
    }
}
