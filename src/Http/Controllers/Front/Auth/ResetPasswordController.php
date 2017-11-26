<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use App\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\ResetPasswordRequest;
use App\Http\Controllers\Auth\ResetPasswordController as BaseResetPasswordController;

class ResetPasswordController extends BaseResetPasswordController
{
    /**
     * Отображаем форму для сброса пароля.
     *
     * @param \Illuminate\Http\Request $request
     * @param string|null $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null): View
    {
        $seoService = app()->make('SEOService');

        return view('admin::front.auth.reset')->with([
            'SEO' => $seoService->getTags(null),
            'token' => $token,
        ]);
    }

    /**
     * Сбрасываем пользовательский пароль.
     *
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function resetCustom(ResetPasswordRequest $request)
    {
        $response = $this->broker()->reset(
            $this->credentialsFields($request), function ($user, $password) {
                $this->resetPasswordWithoutLogin($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
            ? $this->sendResetResponseJSON($response)
            : $this->sendResetFailedResponseJSON($response);
    }

    /**
     * Получаем необходимые поля из запроса.
     *
     * @param ResetPasswordRequest $request
     * @return array
     */
    protected function credentialsFields(ResetPasswordRequest $request): array
    {
        return $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
    }

    /**
     * Сохраняем новый пользовательский пароль.
     *
     * @param User $user
     * @param string $password
     */
    protected function resetPasswordWithoutLogin(User $user, string $password): void
    {
        $user->password = bcrypt($password);
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Ответ при удачном сбросе пароля.
     *
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponseJSON(string $response): JsonResponse
    {
        return response()->json([
            'success' => trans($response),
        ]);
    }

    /**
     * Ответ при неудачном сбросе пароля.
     *
     * @param $response
     * @throws ValidationException
     */
    protected function sendResetFailedResponseJSON(string $response)
    {
        throw ValidationException::withMessages([
            'email' => [
                trans($response),
            ],
        ]);
    }
}
