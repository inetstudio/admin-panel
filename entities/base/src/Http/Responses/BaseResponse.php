<?php

namespace InetStudio\AdminPanel\Base\Http\Responses;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Responsable;

/**
 * Class BaseResponse.
 */
abstract class BaseResponse implements Responsable
{
    /**
     * @var bool
     */
    protected $abortOnEmptyData = false;

    /**
     * @var bool
     */
    protected $render = false;

    /**
     * @var string
     */
    protected $view;

    /**
     * @param $request
     *
     * @return array
     */
    abstract protected function prepare($request): array;

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|void
     *
     * @throws Throwable
     */
    public function toResponse($request)
    {
        $data = $this->prepare($request);

        if ($request->ajax() && ! $this->render) {
            return response()->json($data);
        } elseif ($this->render) {
            $content = view($this->view, $data)->render();

            return response($content);
        } else {
            return (empty($data) && $this->abortOnEmptyData) ? abort(404) : response()->view($this->view, $data);
        }
    }
}
