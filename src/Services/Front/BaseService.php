<?php

namespace InetStudio\AdminPanel\Services\Front;

use InetStudio\AdminPanel\Services\CommonService;

/**
 * Class BaseService.
 */
class BaseService extends CommonService
{
    /**
     * BaseService constructor.
     *
     * @param $repository
     */
    public function __construct($repository)
    {
        parent::__construct($repository);
    }
}
