<?php

use Base\Container\Container;

if (!function_exists('config')) {
    function config(string $key): mixed
    {
        return Container::getInstance()->get('config')->$key;
    }
}
