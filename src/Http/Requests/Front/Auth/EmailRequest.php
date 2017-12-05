<?php

namespace InetStudio\AdminPanel\Http\Requests\Front\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailRequest extends FormRequest
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
            'email.required' => 'Поле «E-mail» обязательно для заполнения',
            'email.email' => 'Поле «E-mail» содержит значение в некорректном формате',
            'email.max' => 'Поле «E-mail» не должно превышать 255 символов',
            'email.unique' => 'Пользователь с таким «E-mail» уже существует',
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
            'email' => 'required|email|max:255|unique:users,email',
        ];
    }
}
