<?php

namespace InetStudio\AdminPanel\Models\Traits;

trait HasAdditionalInfo
{
    /*
     * Determine if the media item has a custom property with the given name.
     */
    public function hasAdditionalInfo(string $propertyName): bool
    {
        return array_has($this->additional_info, $propertyName);
    }

    /**
     * Get if the value of additional info with the given name.
     *
     * @param string $propertyName
     * @param mixed $default
     *
     * @return mixed
     */
    public function getAdditionalInfo(string $propertyName, $default = null)
    {
        return array_get($this->additional_info, $propertyName, $default);
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setAdditionalInfo(string $name, $value)
    {
        $additionalInfo = $this->additional_info;

        array_set($additionalInfo, $name, $value);

        $this->additional_info = $additionalInfo;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function forgetAdditionalInfo(string $name)
    {
        $additionalInfo = $this->additional_info;

        array_forget($additionalInfo, $name);

        $this->additional_info = $additionalInfo;

        return $this;
    }
}
