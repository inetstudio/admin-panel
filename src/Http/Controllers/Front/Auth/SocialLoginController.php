<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        $driver = Socialite::driver($provider);

        if ($provider != 'twitter') {
            $driver->stateless()->scopes(['email']);
        }

        return $driver->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $usersService = app()->make('UsersService');

        $driverObj = Socialite::driver($provider);

        if ($provider != 'twitter') {
            $driverObj->stateless();
        }

        $socialUser = $driverObj->user();

        if (! $socialUser->getEmail()) {
            Session::flash('social_user', $socialUser);

            return response()->redirectToRoute('front.oauth.email');
        }

        $authUser = $usersService->createOrGetSocialUser($socialUser, $provider);
        
        Auth::login($authUser, true);

        return response()->redirectTo('/');
    }

    public function askEmail()
    {
        if (! Session::has('social_user')) {
            return response()->redirectTo('/');
        }

        $seoService = app()->make('SEOService');

        return view('admin::front.auth.email')->with([
            'SEO' => $seoService->getTags(null),
        ]);
    }
}
