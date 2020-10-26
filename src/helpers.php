<?php

if (!function_exists('env')) {
    function env($name, $default = null) {
        if (array_key_exists($name, $_ENV)) {
            return $_ENV[$name];
        }

        return $default;
    }
}
