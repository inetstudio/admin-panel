<?php

namespace InetStudio\AdminPanel\Events\Back\Images;

use Illuminate\Queue\SerializesModels;
use InetStudio\AdminPanel\Contracts\Events\Back\Images\UpdateImageEventContract;

/**
 * Class UpdateImageEvent.
 */
class UpdateImageEvent implements UpdateImageEventContract
{
    use SerializesModels;

    public $object;
    public $name;

    /**
     * Create a new event instance.
     *
     * UpdateImageEvent constructor.
     *
     * @param $object
     * @param $name
     */
    public function __construct($object, $name)
    {
        $this->object = $object;
        $this->name = $name;
    }
}
