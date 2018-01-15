<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\RegisterRequest;
use App\Http\Controllers\Auth\RegisterController as BaseRegisterController;

class RegisterController extends BaseRegisterController
{
    /**
     * Регистрация пользователя.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function registerCustom(RegisterRequest $request): JsonResponse
    {
        $user = $this->create($request->all());

        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => trans('admin::activation.activationStatus'),
        ]);
    }

    /**
     * Создаем пользователя.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'activated' => 0,
        ]);
    }
}
