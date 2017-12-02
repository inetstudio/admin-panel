<?php

namespace InetStudio\AdminPanel\Events\Auth;

use Illuminate\Queue\SerializesModels;

class ActivatedEvent
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
     * ActivatedEvent constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
