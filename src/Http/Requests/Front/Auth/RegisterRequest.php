<?php

namespace InetStudio\AdminPanel\Http\Requests\Front\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Определить, авторизован ли пользователь для этого запроса.
     *
     * @return bool
     */
    public function authorize()
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
            'name.required' => 'Поле «Имя» обязательно для заполнения',
            'name.max' => 'Поле «Имя» не должно превышать 255 символов',

            'email.required' => 'Поле «E-mail» обязательно для заполнения',
            'email.email' => 'Поле «E-mail» содержит значение в некорректном формате',
            'email.max' => 'Поле «E-mail» не должно превышать 255 символов',
            'email.unique' => 'Пользователь с таким «E-mail» уже существует',

            'password.required' => 'Поле «Пароль» обязательно для заполнения',
            'password.confirmed' => 'Введенные пароли не совпадают',
            'password.min' => 'Поле «Новый пароль» должно содержать минимум 6 символов',

            'reg-agree.required' => 'Обязательно для заполнения',
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'reg-agree' => 'required',
        ];
    }
}
