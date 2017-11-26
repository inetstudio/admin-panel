<?php

namespace InetStudio\AdminPanel\Events;

use Illuminate\Queue\SerializesModels;

class UnactivatedLogin
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
     * UnactivatedLogin constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
