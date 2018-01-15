<?php

namespace InetStudio\AdminPanel\Events\Auth;

use Illuminate\Queue\SerializesModels;

class UnactivatedLoginEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * UnactivatedLoginEvent constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
