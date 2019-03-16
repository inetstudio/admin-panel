<?php

namespace InetStudio\AdminPanel\Base\Services\Front;

use InetStudio\AdminPanel\Base\Services\BaseService as CommonService;

/**
 * Class BaseService.
 */
class BaseService extends CommonService
{
    /**
     * BaseService constructor.
     *
     * @param $model
     */
    public function __construct($model)
    {
        parent::__construct($model);
    }
}
