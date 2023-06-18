<?php

namespace Base\Contracts\Registration;

use Base\Container\Container;

abstract class Registration
{
    public function __construct(
        Container $container,
        object $config
    ) {
        $this->register($container, $config);
    }

    abstract protected function register(Container $container, object $config): void;
}
