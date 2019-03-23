<?php

namespace InetStudio\AdminPanel\Models\Traits;

use Illuminate\Support\Arr;

/**
 * Trait HasJSONColumns.
 */
trait HasJSONColumns
{
    /**
     * Проверяем наличие значения в JSON колонке.
     *
     * @param string $field
     * @param string $propertyName
     *
     * @return bool
     */
    public function hasJSONData(string $field, string $propertyName): bool
    {
        return Arr::has($this[$field], $propertyName);
    }

    /**
     * Получаем значение из JSON колонки.
     *
     * @param string $field
     * @param string $propertyName
     * @param mixed $default
     *
     * @return mixed
     */
    public function getJSONData(string $field, string $propertyName, $default = null)
    {
        return Arr::get($this[$field], $propertyName, $default);
    }

    /**
     * Устанавливаем значение в JSON колонке.
     *
     * @param string $field
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setJSONData(string $field, string $name, $value): self
    {
        $data = $this[$field];

        Arr::set($data, $name, $value);

        $this[$field] = $data;

        return $this;
    }

    /**
     * Удаляем значение из JSON колонки.
     *
     * @param string $field
     * @param string $name
     *
     * @return $this
     */
    public function forgetJSONData(string $field, string $name): self
    {
        $data = $this[$field];

        Arr::forget($data, $name);

        $this[$field] = $data;

        return $this;
    }
}
