<?php

namespace App\Http\Helpers;

trait ActiveTab
{
    public static function active_class_path($paths, $classes = null)
    {
        foreach ((array) $paths as $path) {
            if (request()->is($path)) {
                return 'class="' . ($classes ? $classes . ' ' : '') . 'active"';
            }
        }
        return $classes ? 'class="' . $classes . '"' : '';
    }
}

?>