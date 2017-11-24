<?php

if (!function_exists('underscoreToCamelCase')) {
    function underscoreToCamelCase($string)
    {
        return preg_replace_callback(
            '/_(.?)/',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $string
        );
    }
}

if (!function_exists('camelCaseToUnderscore')) {
    function camelCaseToUnderscore($string)
    {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}
