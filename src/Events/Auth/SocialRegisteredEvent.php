<?php

namespace InetStudio\AdminPanel\Events\Auth;

use Illuminate\Queue\SerializesModels;

class SocialRegisteredEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * SocialRegisteredEvent constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
