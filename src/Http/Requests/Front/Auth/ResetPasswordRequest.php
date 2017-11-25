<?php

namespace InetStudio\AdminPanel\Http\Requests\Front\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Сообщения об ошибках.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Поле «E-mail» обязательно для заполнения',
            'email.email' => 'Поле «E-mail» содержит значение в некорректном формате',

            'password.required' => 'Поле «Новый пароль» обязательно для заполнения',
            'password.confirmed' => 'Введенные пароли не совпадают',
            'password.min' => 'Поле «Новый пароль» должно содержать минимум 6 символов',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }
}
