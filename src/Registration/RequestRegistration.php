<?php

namespace Base\Registration;

use Base\Container\Container;
use Base\Contracts\Registration\Registration;

class RequestRegistration extends Registration
{
    protected function register(Container $container, object $request): void
    {
        $container->register('request', fn (): object => (object) [
            'method' => $request->method,
            'uri' => $request->uri
        ]);
    }
}
