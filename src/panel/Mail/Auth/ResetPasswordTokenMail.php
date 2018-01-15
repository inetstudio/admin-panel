<?php

namespace InetStudio\AdminPanel\Mail\Auth;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordTokenMail extends Mailable
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
            ->subject('Сброс пароля')
            ->view('admin::mails.auth.reset_password_token', ['token' => $this->token]);
    }
}
