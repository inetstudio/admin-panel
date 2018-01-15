<?php

namespace InetStudio\AdminPanel\Listeners;

use App\Role;

class AttachUserRoleToUser
{
    /**
     * Create the event listener.
     *
     * AttachUserRoleToUser constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param $event
     * @return void
     */
    public function handle($event): void
    {
        $user = $event->user;
        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $user->attachRole($userRole);
        }
    }
}
