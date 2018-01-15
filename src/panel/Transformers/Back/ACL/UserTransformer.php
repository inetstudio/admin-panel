<?php

namespace InetStudio\AdminPanel\Transformers\Back\ACL;

use App\User;
use League\Fractal\TransformerAbstract;

/**
 * Class UserTransformer
 * @package InetStudio\AdminPanel\Transformers\Back\ACL
 */
class UserTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param User $user
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(User $user): array
    {
        $roles = $user->roles->pluck('display_name')->toArray();

        $rolesHTML = '';

        foreach ($roles as $role) {
            $rolesHTML .= '<p>'.$role.'</p>';
        }

        return [
            'id' => (int) $user->id,
            'name' => (string) $user->name,
            'email' => (string) $user->email,
            'roles' => $rolesHTML,
            'actions' => view('admin::back.partials.datatables.users.actions', ['id' => $user->id])->render(),
        ];
    }
}
