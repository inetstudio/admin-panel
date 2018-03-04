<?php

namespace InetStudio\AdminPanel\Transformers\Back\ACL;

use App\Role;
use League\Fractal\TransformerAbstract;

/**
 * Class RoleTransformer.
 */
class RoleTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param Role $role
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(Role $role): array
    {
        return [
            'id' => (int) $role->id,
            'display_name' => (string) $role->display_name,
            'name' => (string) $role->name,
            'description' => (string) $role->description,
            'actions' => view('admin::back.partials.datatables.roles.actions', ['id' => $role->id])->render(),
        ];
    }
}
