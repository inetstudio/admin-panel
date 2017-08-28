<?php

namespace InetStudio\AdminPanel\Transformers;

use App\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    /**
     * @param Role $role
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id' => (int) $role->id,
            'display_name' => (string) $role->display_name,
            'name' => (string) $role->name,
            'description' => (string) $role->description,
            'actions' => view('admin::partials.datatables.roles.actions', ['id' => $role->id])->render(),
        ];
    }
}
