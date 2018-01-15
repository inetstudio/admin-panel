<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use InetStudio\AdminPanel\Events\Auth\SocialActivatedEvent;
use InetStudio\AdminPanel\Events\Auth\UnactivatedLoginEvent;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\EmailRequest;
use InetStudio\Meta\Contracts\Services\Front\MetaServiceContract as FrontMetaServiceContract;

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
        $seoService = app()->make(FrontMetaServiceContract::class);
        $usersService = app()->make('UsersService');

        $driverObj = Socialite::driver($provider);

        if ($provider != 'twitter') {
            $driverObj->stateless();
        }

        try {
            $socialUser = $driverObj->user();
        } catch (\Exception $e) {
            return response()->redirectTo('/?popup=auth');
        }

        $authUser = $usersService->createOrGetSocialUser($socialUser, $provider);

        if (! $authUser) {
            Session::flash('social_user', $socialUser);
            Session::flash('provider', $provider);

            return response()->redirectToRoute('front.oauth.email');
        }

        if (! $authUser->activated) {
            event(new UnactivatedLoginEvent($authUser));

            return view('admin::front.auth.activate', [
                'SEO' => $seoService->getAllTags(null),
                'activation' => [
                    'success' => false,
                    'message' => trans('admin::activation.activationWarning'),
                ],
            ]);
        }

        Auth::login($authUser, true);

        return response()->redirectTo('/');
    }

    public function askEmail()
    {
        if (! Session::has('social_user')) {
            return response()->redirectTo('/');
        }

        $seoService = app()->make(FrontMetaServiceContract::class);

        return view('admin::front.auth.email')->with([
            'SEO' => $seoService->getAllTags(null),
        ]);
    }

    public function approveEmail(EmailRequest $request)
    {
        $usersService = app()->make('UsersService');

        $socialUser = Session::get('social_user');
        $provider = Session::get('provider');
        $email = $request->get('email');

        $authUser = $usersService->createOrGetSocialUser($socialUser, $provider, $email);

        event(new SocialActivatedEvent($authUser));

        return response()->json([
            'success' => true,
            'message' => trans('admin::activation.activationStatus'),
        ]);
    }
}