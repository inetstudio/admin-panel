<?php

namespace InetStudio\AdminPanel\Services\Front\ACL;

use App\User;
use Illuminate\Support\Facades\Hash;
use InetStudio\AdminPanel\Events\Auth\SocialRegisteredEvent;
use InetStudio\AdminPanel\Models\ACL\UserSocialProfileModel;

class UsersService
{
    private $user;

    /**
     * UsersService constructor.
     */
    public function __construct()
    {
        $this->user = \Auth::user();
    }

    /**
     * Проверяем принадлежность пользователя к администрации.
     *
     * @return string
     */
    public function isAdmin(): string
    {
        $user = $this->user;

        return ($user && $user->hasRole('admin')) ? 'admin' : 'user';
    }

    /**
     * Возвращаем пользователя.
     *
     * @return \App\User|null
     */
    public function getUser()
    {
        $user = $this->user;

        return ($user) ? $user : null;
    }

    /**
     * Возвращаем id пользователя.
     *
     * @param string $email
     * @return int
     */
    public function getUserId(string $email = ''): int
    {
        $user = $this->user;

        if ($email) {
            $user = User::where('email', $email)->first();
        }

        return ($user) ? $user->id : 0;
    }

    /**
     * Возвращаем имя пользователя.
     *
     * @param $request
     * @return string
     */
    public function getUserName($request = null): string
    {
        $user = $this->user;

        return ($request && $request->has('name')) ? strip_tags($request->get('name')) : (($user) ? $user->name : '');
    }

    /**
     * Возвращаем email пользователя.
     *
     * @param $request
     * @return string
     */
    public function getUserEmail($request = null): string
    {
        $user = $this->user;

        return ($request && $request->has('email')) ? strip_tags($request->get('email')) : (($user) ? $user->email : '');
    }

    /**
     * Создаем или получаем пользователя социальной сети.
     *
     * @param $socialUser
     * @param string $providerName
     * @param string $approveEmail
     * @return mixed
     */
    public function createOrGetSocialUser($socialUser, string $providerName, string $approveEmail = '')
    {
        $email = ($approveEmail) ? $approveEmail : $socialUser->getEmail();

        $socialProfile = UserSocialProfileModel::where('provider', $providerName)->where('provider_id', $socialUser->getId())->first();

        if (! $email && ! $socialProfile) {
            return;
        }

        if (! $socialProfile) {
            $socialProfile = UserSocialProfileModel::create([
                'provider' => $providerName,
                'provider_id' => $socialUser->getId(),
                'provider_email' => $email,
            ]);
        }

        $user = $socialProfile->user;

        if (! $user) {
            $user = User::where('email', $email)->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialProfile->provider_email,
                'password' => Hash::make($socialUser->getName().config('app.key').$socialUser->getEmail()),
                'activated' => ($approveEmail) ? 0 : 1,
            ]);

            event(new SocialRegisteredEvent($user));
        } else {
            if (! $user->hasRole('social_user')) {
                $user->update([
                    'activated' => 1,
                ]);
            }
        }

        $socialProfile->user()->associate($user);
        $socialProfile->save();

        return $user;
    }
}
