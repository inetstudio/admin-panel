<?php

namespace InetStudio\AdminPanel\Base\Http\Responses;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;

/**
 * Class BaseResponse.
 */
abstract class BaseResponse implements Responsable
{
    /**
     * @var string
     */
    protected $view;

    /**
     * Prepare response data.
     *
     * @return array
     */
    abstract protected function prepare() : array;

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $data = $this->prepare();

        if (request()->ajax()) {
            return response()->json($data);
        } else {
            return response()->view($this->view, $data);
        }
    }
}
