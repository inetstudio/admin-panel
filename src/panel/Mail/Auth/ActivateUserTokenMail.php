<?php

namespace InetStudio\AdminPanel\Mail\Auth;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ActivateUserTokenMail extends Mailable
{
    use SerializesModels;

    protected $token;

    /**
     * NewCommentMail constructor.
     *
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): self
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject(config('mail.activation.subject') ?? 'Активация аккаунта')
            ->view('admin::mails.auth.activate_user_token', ['token' => $this->token]);
    }
}
