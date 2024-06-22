<?php

namespace Base\Support;

use Base\DataTransferObjects\InputServerDto;

readonly class Request
{
    public object $request;
    
    public function __construct(object $config, InputServerDto $inputServer)
    {
        $this->request = (object) [
            'method' => $inputServer->method,
            'uri' => $inputServer->uri,
            'module' => (new ModuleRegistration($config, $inputServer->uri))->getRequestModule()
        ];
    }
}
