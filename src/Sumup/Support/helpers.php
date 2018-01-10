<?php

if (!function_exists('snakeCaseToCamelCase')) {
    function snakeCaseToCamelCase($string)
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

if (!function_exists('camelCaseToSnakeCase')) {
    function camelCaseToSnakeCase($string)
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

if (!function_exists('isAssociativeArray')) {
    function isAssociativeArray(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }
}
