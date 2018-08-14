<?php

namespace InetStudio\AdminPanel\Models\Traits;

use Illuminate\Support\Facades\Config;

/**
 * Trait HasDynamicRelationships.
 */
trait HasDynamicRelationships
{
    /**
     * Handle dynamic method calls into the model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters) {
        $config = implode( '.', [$this->config.'.relationships', $method]);

        if (Config::has($config)) {
            $data = Config::get($config);

            $model = isset($data['model']) ? [app()->make($data['model'])] : [];
            $params = isset($data['params']) ? $data['params'] : [];

            return call_user_func_array([$this, $data['relationship']], array_merge($model, $params));
        }

        return parent::__call($method, $parameters);
    }

    /**
     * Get an attribute from the model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        $config = implode( '.', [$this->config.'.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationValue($key);
        }

        return parent::getAttribute($key);
    }

    /**
     * Get a relationship.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getRelationValue($key)
    {
        if ($this->relationLoaded($key)) {
            return $this->relations[$key];
        }

        $config = implode( '.', [$this->config.'.relationships', $key]);

        if (Config::has($config)) {
            return $this->getRelationshipFromMethod($key);
        }

        return parent::getRelationValue($key);
    }
}
