<?php

namespace InetStudio\AdminPanel\Services\Front\Auth;

use Carbon\Carbon;
use InetStudio\AdminPanel\Models\Auth\UserActivationModel;

class UsersActivationsService
{
    public function getActivation($user)
    {
        return $user->activation;
    }

    public function getActivationByToken($token)
    {
        return UserActivationModel::where('token', $token)->first();
    }

    public function deleteActivation($token)
    {
        UserActivationModel::where('token', $token)->delete();
    }

    public function createActivation($user)
    {
        $activation = $this->getActivation($user);

        if (! $activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);
    }

    protected function getToken()
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    private function regenerateToken($user)
    {
        $token = $this->getToken();

        $user->activation->update([
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function createToken($user)
    {
        $token = $this->getToken();

        UserActivationModel::create([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => new Carbon()
        ]);

        return $token;
    }
}
