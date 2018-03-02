<?php

namespace InetStudio\AdminPanel\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class CropRequired.
 */
class CropRequired implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $filenameInput = str_replace('crop', 'filename', $attribute);

        if (request()->input($filenameInput) === null) {
            return true;
        }

        foreach ($value as $values) {
            if (! empty($values)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Необходимо выбрать область отображения';
    }
}