<?php

namespace Base\Support;

readonly class Request
{
    public object $request;
    
    public function __construct(object $config, string $method, string $uri)
    {
        $this->request = (object) [
            'method' => $method,
            'uri' => $uri,
            'module' => (new ModuleRegistration($config, $uri))->getRequestModule()
        ];
    }
}
