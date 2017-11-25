<?php

namespace InetStudio\AdminPanel\Notifications\Auth;

use App\User;
use Illuminate\Notifications\Notification;
use InetStudio\AdminPanel\Mail\Auth\ResetPasswordTokenMail;

class ResetPasswordTokenNotification extends Notification
{
    /**
     * The password reset token.
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
     * @return ResetPasswordTokenMail
     */
    public function toMail($notifiable): ResetPasswordTokenMail
    {
        return (new ResetPasswordTokenMail($this->token))->to($this->user->email);
    }
}
