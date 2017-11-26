<?php

namespace InetStudio\AdminPanel\Listeners\Auth;

use Illuminate\Auth\Events\Registered;
use InetStudio\AdminPanel\Notifications\Auth\ActivateUserTokenNotification;

class SendActivateNotificationListener
{
    protected $resendAfter = 24;

    /**
     * SendActivateNotification constructor.
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
    public function handle(Registered $event)
    {
        $usersActivationsService = app()->make('UsersActivationsService');

        $user = $event->user;

        if ($user->activated || ! $this->shouldSend($usersActivationsService, $user)) {
            return;
        }

        $token = $usersActivationsService->createActivation($user);

        $user->notify(new ActivateUserTokenNotification($token, $user));
    }

    /**
     * Проверяем, нужно ли отправлять письмо.
     *
     * @param $usersActivationsService
     * @param $user
     * @return bool
     */
    private function shouldSend($usersActivationsService, $user): bool
    {
        $activation = $usersActivationsService->getActivation($user);

        return $activation === null || strtotime($activation->created_at) + 60 * 60 * $this->resendAfter < time();
    }
}
