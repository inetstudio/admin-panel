<?php

namespace InetStudio\AdminPanel\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * @param User $user
     * @return array
     */
    public function transform(User $user)
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
            'actions' => view('admin::partials.datatables.users.actions', ['id' => $user->id])->render(),
        ];
    }
}
