<?php

namespace InetStudio\AdminPanel\Listeners;

use App\Role;
use Illuminate\Auth\Events\Registered;

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
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        $userRole = Role::where('name', 'user')->first();

        if ($userRole) {
            $user->attachRole($userRole);
        }
    }
}
