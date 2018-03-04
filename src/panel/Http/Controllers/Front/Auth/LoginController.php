<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InetStudio\AdminPanel\Events\Auth\UnactivatedLoginEvent;
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

        if ($user = $this->checkActivation($baseRequest)) {
            return $this->sendNeedActivationResponse($user);
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

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Проверяем активацию пользователя.
     *
     * @param Request $request
     * @return null
     */
    public function checkActivation(Request $request)
    {
        $provider = \Auth::getProvider();

        $credentials = $this->credentials($request);
        $user = $provider->retrieveByCredentials($credentials);

        if (! is_null($user) && $provider->validateCredentials($user, $credentials)) {
            if (! $user->activated) {
                return $user;
            }
        }
    }

    /**
     * Ошибка активации аккаунта.
     *
     * @throws ValidationException
     */
    public function sendNeedActivationResponse($user)
    {
        event(new UnactivatedLoginEvent($user));

        throw ValidationException::withMessages([
            'email' => [
                trans('admin::activation.activationWarning'),
            ],
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
