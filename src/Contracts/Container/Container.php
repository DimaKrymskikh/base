<?php

namespace Base\Contracts\Container;

interface Container
{
    public function register(string $key, \Closure $callback): void;
    public function get(string $key): mixed;
}
