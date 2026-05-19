<?php

use Base\Container\Container;

if (!function_exists('config')) {
    function config(string $key): mixed
    {
        return Container::getInstance()->get('config')->$key;
    }
}

if (!function_exists('wrapper_implode')) {
    function wrapper_implode(string $separator, array $array): string
    {
        return implode($separator, $array);
    }
}

if (!function_exists('wrapper_in_array')) {
    function wrapper_in_array(mixed $needle, array $haystack): bool
    {
        return in_array($needle, $haystack, true);
    }
}

if (!function_exists('wrapper_class_exists')) {
    function wrapper_class_exists(string $class): bool
    {
        return class_exists($class);
    }
}

if (!function_exists('wrapper_strtr')) {
    function wrapper_strtr(string $string, array $replace): string
    {
        return strtr($string, $replace);
    }
}
