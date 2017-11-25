<?php

namespace InetStudio\AdminPanel\Notifications\Auth;

use App\User;
use Illuminate\Notifications\Notification;
use InetStudio\AdminPanel\Mail\Auth\ActivateUserTokenMail;

class ActivateUserTokenNotification extends Notification
{
    /**
     * The activate user token.
     *
     * @var string
     */
    public $token;
    public $user;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @param User $user
     */
    public function __construct($token, User $user)
    {
        $this->token = $token;
        $this->user = $user;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param $notifiable
     * @return ActivateUserTokenMail
     */
    public function toMail($notifiable): ActivateUserTokenMail
    {
        return (new ActivateUserTokenMail($this->token))->to($this->user->email);
    }
}
