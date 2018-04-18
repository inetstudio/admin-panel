<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\Auth\RegisterController as BaseRegisterController;
use InetStudio\AdminPanel\Contracts\Http\Requests\Front\RegisterRequestContract;

class RegisterController extends BaseRegisterController
{
    /**
     * Регистрация пользователя.
     *
     * @param RegisterRequestContract $request
     * @return JsonResponse
     */
    public function registerCustom(RegisterRequestContract $request): JsonResponse
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
            'password' => Hash::make($data['password']),
            'activated' => 0,
        ]);
    }
}
