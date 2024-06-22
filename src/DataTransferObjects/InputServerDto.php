<?php

namespace Base\DataTransferObjects;

readonly class InputServerDto
{
    public function __construct(
        public string $method,
        public string $uri,
    ) 
    {}
}
