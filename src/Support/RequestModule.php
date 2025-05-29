<?php

namespace Base\Support;

use Base\Server\ServerRequestInterface;

readonly class RequestModule
{
    public object $request;
    
    public function __construct(object $config, ServerRequestInterface $serverRequest)
    {
        $method = $serverRequest->getMethod();
        $uri = $serverRequest->getUri();
        
        $this->request = (object) [
            'method' => $method,
            'uri' => $uri,
            'module' => (new ModuleRegistration($config, $uri))->getRequestModule()
        ];
    }
}
