<?php

namespace InetStudio\AdminPanel\Requests\ACL;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

class SavePermissionRequest extends FormRequest
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
            'display_name.max' => 'Поле «Название» не должно превышать 255 символов',
            'description.max' => 'Поле «Описание» не должно превышать 255 символов',
            'name.required' => 'Поле «Алиас» обязательно для заполнения',
            'name.max' => 'Поле «Алиас» не должно превышать 255 символов',
            'name.unique' => 'Такое значение поля «Алиас» уже существует',
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
        return [
            'display_name' => 'max:255',
            'description' => 'max:255',
            'name' => 'required|max:255|unique:permissions,name,'.$request->get('permission_id'),
        ];
    }
}
