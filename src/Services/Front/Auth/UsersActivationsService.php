<?php

namespace InetStudio\AdminPanel\Services\Front\Auth;

use App\User;
use Carbon\Carbon;
use InetStudio\AdminPanel\Models\Auth\UserActivationModel;

class UsersActivationsService
{
    /**
     * Получаем активацию пользователя.
     *
     * @param User $user
     * @return UserActivationModel|null
     */
    public function getActivation(User $user): ?UserActivationModel
    {
        return $user->activation;
    }

    /**
     * Получаем активацию по токену.
     *
     * @param string $token
     * @return UserActivationModel|null
     */
    public function getActivationByToken(string $token): ?UserActivationModel
    {
        return UserActivationModel::where('token', $token)->first();
    }

    /**
     * Удаляем активацию.
     *
     * @param string $token
     */
    public function deleteActivation(string $token): void
    {
        UserActivationModel::where('token', $token)->delete();
    }

    /**
     * Создаем активацию.
     *
     * @param User $user
     * @return string
     */
    public function createActivation(User $user): string
    {
        $activation = $this->getActivation($user);

        if (! $activation) {
            return $this->createToken($user);
        }

        return $this->regenerateToken($user);
    }

    /**
     * Генерация токена.
     *
     * @return string
     */
    protected function getToken(): string
    {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    /**
     * Обновление токена.
     *
     * @param User $user
     * @return string
     */
    private function regenerateToken(User $user): string
    {
        $token = $this->getToken();

        $user->activation->update([
            'token' => $token,
            'created_at' => new Carbon(),
        ]);

        return $token;
    }

    /**
     * Создание токена.
     *
     * @param User $user
     * @return string
     */
    private function createToken(User $user): string
    {
        $token = $this->getToken();

        UserActivationModel::create([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => new Carbon(),
        ]);

        return $token;
    }
}
