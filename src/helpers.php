<?php

use Illuminate\Support\Facades\Blade;

if (! function_exists('blade_string')) {
    function blade_string($value, array $args = array())
    {
        $generated = Blade::compileString($value);

        ob_start() and extract($args, EXTR_SKIP);

        try {
            eval('?>'.$generated);
        } catch (\Exception $e) {
            ob_get_clean(); throw $e;
        }

        $content = ob_get_clean();

        return $content;
    }
}

if (! function_exists('get_correct_word')) {
    function get_correct_word($num, array $variants)
    {
        $num = abs($num);
        $val = $num % 100;

        if ($val > 10 && $val < 20) {
            return $variants[2];
        } else {
            $val = $num % 10;

            if ($val == 1) {
                return $variants[0];
            } elseif ($val > 1 && $val < 5) {
                return $variants[1];
            } else {
                return $variants[2];
            }
        }
    }
}
