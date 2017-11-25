<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Registered;
use InetStudio\AdminPanel\Services\Front\Auth\UsersActivationsService;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\RegisterRequest;
use App\Http\Controllers\Auth\RegisterController as BaseRegisterController;

class RegisterController extends BaseRegisterController
{
    /**
     * Регистрация пользователя.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerCustom(RegisterRequest $request): JsonResponse
    {
        event(new Registered($user = $this->create($request->all())));

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
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
        ]);
    }

    public function activate(UsersActivationsService $usersActivationsService, $token)
    {
        $activation = $usersActivationsService->getActivationByToken($token);

        if ($activation === null) {
            return null;
        }

        $usersActivationsService->deleteActivation($token);

        return 'test';
    }
}
