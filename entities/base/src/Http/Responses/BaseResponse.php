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
     * @var array
     */
    protected $cookies = [];

    /**
     * @param $request
     *
     * @return array
     */
    abstract protected function prepare($request): array;

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|null
     *
     * @throws Throwable
     */
    public function toResponse($request)
    {
        $data = $this->prepare($request);

        $response = response();

        if ($request->ajax() && ! $this->render) {
            $response = $response->json($data);
        } elseif ($this->render) {
            $content = view($this->view, $data)->render();

            $response = $response->make($content);
        } else {
            $response = (empty($data) && $this->abortOnEmptyData) ? null : $response->view($this->view, $data);
        }

        if (! $response) {
            abort(404);
        }

        foreach ($this->cookies ?? [] as $name => $value) {
            $response->cookie($name, $value);
        }

        return $response;
    }
}
