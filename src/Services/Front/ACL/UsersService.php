<?php

namespace InetStudio\AdminPanel\Services\Front\ACL;

use App\User;
use Illuminate\Http\Request;
use InetStudio\AdminPanel\Models\ACL\UserSocialProfileModel;
use InetStudio\AdminPanel\Events\Auth\SocialRegisteredEvent;

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
    public function getUserId($email = ''): int
    {
        $user = $this->user;

        if (! $user && $email) {
            $user = User::where('email', $email)->first();
        }

        return ($user) ? $user->id : 0;
    }

    /**
     * Возвращаем имя пользователя.
     *
     * @param Request $request
     * @return mixed|string
     */
    public function getUserName(Request $request): string
    {
        $user = $this->user;

        return ($user) ? $user->name : strip_tags($request->get('name'));
    }

    /**
     * Возвращаем email пользователя.
     *
     * @param Request $request
     * @return mixed|string
     */
    public function getUserEmail(Request $request): string
    {
        $user = $this->user;

        return ($user) ? $user->email : strip_tags($request->get('email'));
    }

    /**
     * Создаем или получаем пользователя социальной сети.
     *
     * @param $socialUser
     * @param $providerName
     * @param $approveEmail
     * @return mixed
     */
    public function createOrGetSocialUser($socialUser, $providerName, $approveEmail = '')
    {
        $email = ($approveEmail) ? $approveEmail : $socialUser->getEmail();

        $socialProfile = UserSocialProfileModel::where('provider', $providerName)->where('provider_id', $socialUser->getId())->first();

        if (! $email && ! $socialProfile) {
            return null;
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
                'password' => bcrypt($socialUser->getName().config('app.key').$socialUser->getEmail()),
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
