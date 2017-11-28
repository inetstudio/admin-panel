<?php

namespace InetStudio\AdminPanel\Services\Front\ACL;

use App\User;
use Illuminate\Http\Request;
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
     * @param $providerObj
     * @param $providerName
     * @return mixed
     */
    public function createOrGetSocialUser($providerObj, $providerName)
    {
        $providerUser = $providerObj->user();

        $socialProfile = UserSocialProfileModel::updateOrCreate([
            'provider' => $providerName,
            'provider_id' => $providerUser->getId(),
        ], [
            'provider_email' => $providerUser->getEmail(),
        ]);

        $user = $socialProfile->user;

        if (! $user) {
            $user = User::create([
                'name' => $providerUser->getName(),
                'email' => ($providerUser->getEmail()) ? $providerUser->getEmail() : time().'@default.mail',
                'password' => bcrypt($providerUser->getName().$providerUser->getEmail()),
                'activated' => 1,
            ]);

            $socialProfile->user()->associate($user);
            $socialProfile->save();
        }

        return $user;
    }
}
