<?php

namespace InetStudio\AdminPanel\Listeners;

use App\Role;
use InetStudio\AdminPanel\Events\Auth\SocialRegisteredEvent;

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
     * @param SocialRegisteredEvent $event
     * @return void
     */
    public function handle(SocialRegisteredEvent $event): void
    {
        $user = $event->user;
        $userRole = Role::where('name', 'social_user')->first();

        if ($userRole) {
            $user->attachRole($userRole);
        }
    }
}
