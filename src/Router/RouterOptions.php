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
        $this->patternChunks = explode('/', mb_trim($pattern, '/'));
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
    
    /**
     * Находит число совпадений частей паттерна с частями полученного uri.
     * 
     * @param array $requestUri
     * @return int
     */
    public function getNumberOfMatches(array $requestUri): int
    {
        $n = 0;
        
        array_map(function ($part, $key) use (&$n, $requestUri) { 
            // Если часть паттерна заключена в фигурные скобки, то соответствующая часть $uri - аргумент экшена
            if (str_starts_with($part, '{') && str_ends_with($part, '}')) {
                $this->pushActionArguments($requestUri[$key]);
                $n++;
            // Если часть паттерна без фигурных скобок, то она должна равняться соответствующей части $uri
            } elseif ($this->patternChunks[$key] === $requestUri[$key]) {
                $n++;
            }
        }, array_values($this->patternChunks), array_keys($this->patternChunks));
        
        return $n;
    }
}
