<?php

namespace InetStudio\AdminPanel\Http\Controllers\Front\Auth;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use InetStudio\AdminPanel\Http\Requests\Front\Auth\ForgotPasswordRequest;
use App\Http\Controllers\Auth\ForgotPasswordController as BaseForgotPasswordController;

class ForgotPasswordController extends BaseForgotPasswordController
{
    /**
     * Отправляем пользователю ссылку для сброса пароля.
     *
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmailCustom(ForgotPasswordRequest $request)
    {
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponseJSON($response)
            : $this->sendResetLinkFailedResponseJSON($response);
    }

    /**
     * Ответ при успешной отправке ссылки.
     *
     * @param string $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponseJSON(string $response): JsonResponse
    {
        return response()->json([
            'success' => trans($response),
        ]);
    }

    /**
     * Ответ при неудачной отправке ссылки.
     *
     * @param string $response
     * @throws ValidationException
     */
    protected function sendResetLinkFailedResponseJSON(string $response)
    {
        throw ValidationException::withMessages([
            'email' => [
                trans($response),
            ],
        ]);
    }
}
