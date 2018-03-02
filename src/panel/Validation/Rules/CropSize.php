<?php

namespace InetStudio\AdminPanel\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class CropSize.
 */
class CropSize implements Rule
{
    protected $width;
    protected $height;
    protected $mode;
    protected $cropName;

    protected $aliases = [
        'min' => 'Минимальный',
        'fix' => 'Фиксированный',
    ];

    /**
     * CropSize constructor.
     *
     * @param int $width
     * @param int $height
     * @param string $mode
     * @param string $cropName
     */
    public function __construct(int $width, int $height, string $mode, string $cropName)
    {
        $this->width = $width;
        $this->height = $height;
        $this->mode = $mode;
        $this->cropName = $cropName;
    }

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
        $crop = json_decode($value, true);

        switch ($this->mode) {
            case 'min':
                if (round($crop['width']) < $this->width or round($crop['height']) < $this->height) {
                    return false;
                }
                break;
            case 'fixed':
                if (round($crop['width']) != $this->width and round($crop['height']) != $this->height) {
                    return false;
                }
                break;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $cropName = ($this->cropName !== '') ? ' «'.$this->cropName.'» ' : ' ';

        return $this->aliases[$this->mode].' размер области'.$cropName.'— '.$this->width.'x'.$this->height.' пикселей';
    }
}