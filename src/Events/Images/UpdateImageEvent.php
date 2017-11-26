<?php

namespace InetStudio\AdminPanel\Events\Images;

use Illuminate\Queue\SerializesModels;

class UpdateImageEvent
{
    use SerializesModels;

    public $object;
    public $name;

    /**
     * Create a new event instance.
     *
     * UpdateImageEvent constructor.
     * @param $object
     * @param $name
     */
    public function __construct($object, $name)
    {
        $this->object = $object;
        $this->name = $name;
    }
}
