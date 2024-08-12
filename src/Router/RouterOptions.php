<?php

namespace Base\Router;

final class RouterOptions
{
    readonly public string $method;
    readonly public array $patternChunks;
    readonly public string $controller;
    readonly public string $action;
    
    private array $actionArguments = [];


    public function __construct(string $method, string $pattern, string $controller, string $action = 'index')
    {
        $this->method = $method;
        $this->patternChunks = explode('/', trim($pattern, '/'));
        $this->controller = $controller;
        $this->action = $action;
    }
    
    public function getActionArguments(): array
    {
        return $this->actionArguments;
    }
    
    public function pushActionArguments(mixed $value): void
    {
        $this->actionArguments[] = $value;
    }
}
