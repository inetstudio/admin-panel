<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\LoginRequest;
use App\Http\Controllers\Auth\LoginController as BaseLoginController;

class LoginController extends BaseLoginController
{
    /**
     * Авторизация пользователя.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     */
    public function loginCustom(LoginRequest $request)
    {
        $baseRequest = request();

        if ($this->hasTooManyLoginAttempts($baseRequest)) {
            $this->fireLockoutEvent($baseRequest);

            return $this->sendLockoutResponse($baseRequest);
        }

        if ($this->attemptLogin($baseRequest)) {
            return $this->sendLoginResponseJSON($baseRequest);
        }

        $this->incrementLoginAttempts($baseRequest);

        return $this->sendFailedLoginResponse($baseRequest);
    }

    /**
     * Ответ при удачной авторизации.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponseJSON(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: response()->json([
                'success' => true,
            ]);
    }

    /**
     * Выход пользователя.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return response()->json([
            'success' => true,
        ]);
    }
}
