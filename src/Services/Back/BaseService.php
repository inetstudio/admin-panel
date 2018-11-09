<?php

namespace InetStudio\AdminPanel\Services\Back;

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

    /**
     * Удаляем модель.
     *
     * @param int $id
     *
     * @return bool|null
     */
    public function destroy(int $id): ?bool
    {
        return $this->repository->destroy($id);
    }
}
