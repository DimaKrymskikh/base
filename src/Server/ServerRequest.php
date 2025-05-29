<?php

namespace Base\Server;

final class ServerRequest implements FilterRequestInterface, ServerRequestInterface
{
    private string $method;
    private string $uri;
    private string $protocol;

    public function __construct()
    {
        $this->method = strtoupper(filter_input(INPUT_SERVER, 'REQUEST_METHOD'));
        $this->uri = trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/');
        $this->protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    }
    
    public function getMethod(): string
    {
        return $this->method;
    }
    
    public function getUri(): string
    {
        return $this->uri;
    }
    
    public function getProtocol(): string
    {
        return $this->protocol;
    }
    
    public function filterInputGet(string $name): string
    {
        return filter_input(INPUT_GET, $name) ?: '';
    }

    public function filterInputPost(string $name): string
    {
        return filter_input(INPUT_POST, $name) ?: '';
    }
}
