<?php

namespace InetStudio\AdminPanel\Http\Responses\Back;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\AdminPanel\Contracts\Http\Responses\Back\ConfigResponseContract;

/**
 * Class ConfigResponse.
 */
class ConfigResponse implements ConfigResponseContract, Responsable
{
    /**
     * @var
     */
    private $data;

    /**
     * ConfigResponse constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при запросе конфига.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json($this->data);
    }
}
