<?php

namespace InetStudio\AdminPanel\Models\Traits;

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
        return array_has($this[$field], $propertyName);
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
        return array_get($this[$field], $propertyName, $default);
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

        array_set($data, $name, $value);

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

        array_forget($data, $name);

        $this[$field] = $data;

        return $this;
    }
}
