<?php

use Illuminate\Support\Facades\Blade;

if (! function_exists('static_asset')) {
    function static_asset($asset)
    {
        if (! config('app.static_url'))
            return asset($asset);

        $static = config('app.static_url');

        return static_path($static, $asset).(config('admin.release') ? '?v='.config('admin.release') : '');
    }
}

if (! function_exists('static_path')) {
    function static_path($static, $asset)
    {
        return $static . "/" . ltrim($asset, "/");
    }
}

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
