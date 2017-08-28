<?php

namespace InetStudio\AdminPanel\Requests\ACL;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SaveUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
    public function messages()
    {
        return [
            'name.required' => 'Поле «Имя» обязательно для заполнения',
            'name.string' => 'Поле «Имя» должно быть строкой',
            'name.max' => 'Поле «Имя» не должно превышать 255 символов',

            'email.required' => 'Поле «Email» обязательно для заполнения',
            'email.email' => 'Поле «Email» содержит некорректный почтовый адрес',
            'email.max' => 'Поле «Email» не должно превышать 255 символов',
            'email.unique' => 'Такой почтовый адрес уже существует',

            'password.required' => 'Поле «Пароль» обязательно для заполнения',
            'password.min' => 'Минимальный размер поля "Пароль" 6 символов',
            'password.confirmed' => 'Введенные пароли не совпадают',

            'roles_id.array' => 'Поле «Роли» должно быть массивом',

            'permissions_id.array' => 'Поле «Права» должно быть массивом',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$request->get('user_id'),
            'permissions_id' => 'nullable|array',
            'roles_id' => 'nullable|array',
            'password' => 'nullable|min:6|confirmed',
        ];

        if (! $request->has('user_id')) {
            $rules['password'] .= '|required';
        }

        return $rules;
    }
}
