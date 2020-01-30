<?php

namespace InetStudio\AdminPanel\Base\Models\Traits;

use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Trait HasDynamicRelations.
 */
trait HasDynamicRelations
{
    /**
     * Store the relations
     *
     * @var array
     */
    private static $dynamicRelations = [];

    /**
     * Add a new relation
     *
     * @param $name
     * @param $closure
     */
    public static function addDynamicRelation($name, $closure)
    {
        static::$dynamicRelations[$name] = $closure;
    }

    /**
     * Determine if a relation exists in dynamic relationships list
     *
     * @param $name
     *
     * @return bool
     */
    public static function hasDynamicRelation($name): bool
    {
        return array_key_exists($name, static::$dynamicRelations);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters) {
        if (static::hasDynamicRelation($method)) {
            return call_user_func(static::$dynamicRelations[$method], $this);
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
        if (static::hasDynamicRelation($key)) {
            $this->getRelationValue($key);
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
        if (static::hasDynamicRelation($key)) {
            if ($this->relationLoaded($key)) {
                return $this->relations[$key];
            }

            return $this->getRelationshipFromMethod($key);
        }

        return parent::getRelationValue($key);
    }
}
