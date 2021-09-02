<?php

namespace InetStudio\AdminPanel\Base\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct(
        public Application $app
    ) {}

    protected function process($request, $operation, $response)
    {
        $data = $request->getDataObject();

        $result = $operation->execute($data);

        $response->setResult($result);

        return $response;
    }
}
