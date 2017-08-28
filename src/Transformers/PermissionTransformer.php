<?php

namespace InetStudio\AdminPanel\Transformers;

use App\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * @param Permission $permission
     * @return array
     */
    public function transform(Permission $permission)
    {
        return [
            'id' => (int) $permission->id,
            'display_name' => (string) $permission->display_name,
            'name' => (string) $permission->name,
            'description' => (string) $permission->description,
            'actions' => view('admin::partials.datatables.permissions.actions', ['id' => $permission->id])->render(),
        ];
    }
}
