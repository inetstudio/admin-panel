<?php

namespace InetStudio\AdminPanel\Transformers\Back\ACL;

use App\Permission;
use League\Fractal\TransformerAbstract;

class PermissionTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param Permission $permission
     * @return array
     */
    public function transform(Permission $permission): array
    {
        return [
            'id' => (int) $permission->id,
            'display_name' => (string) $permission->display_name,
            'name' => (string) $permission->name,
            'description' => (string) $permission->description,
            'actions' => view('admin::back.partials.datatables.permissions.actions', ['id' => $permission->id])->render(),
        ];
    }
}
