<?php

namespace InetStudio\AdminPanel\Http\Requests\Back\ACL;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SaveRoleRequest extends FormRequest
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
            'display_name.max' => 'Поле «Название» не должно превышать 255 символов',
            'description.max' => 'Поле «Описание» не должно превышать 255 символов',
            'name.required' => 'Поле «Алиас» обязательно для заполнения',
            'name.max' => 'Поле «Алиас» не должно превышать 255 символов',
            'name.unique' => 'Такое значение поля «Алиас» уже существует',
            'permissions_id.array' => 'Поле «Права» должно быть массивом',
        ];
    }

    /**
     * Правила проверки запроса.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request): array
    {
        return [
            'display_name' => 'max:255',
            'description' => 'max:255',
            'name' => 'required|max:255|unique:roles,name,'.$request->get('role_id'),
            'permissions_id' => 'nullable|array',
        ];
    }
}
