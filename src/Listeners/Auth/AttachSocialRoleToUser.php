<?php

namespace InetStudio\AdminPanel\Listeners;

use App\Role;

class AttachSocialRoleToUser
{
    /**
     * Create the event listener.
     *
     * AttachSocialRoleToUser constructor.
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
        $userRole = Role::where('name', 'social_user')->first();

        if ($userRole) {
            $user->attachRole($userRole);
        }
    }
}
