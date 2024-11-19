<?php

namespace Base\Support\Filter;

final class FilterRequest implements FilterRequestInterface
{
    private static ?FilterRequest $instance = null;
    
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    private function __construct()
    {}

    public function filterInputGet(string $name): string
    {
        return filter_input(INPUT_GET, $name) ?: '';
    }

    public function filterInputPost(string $name): string
    {
        return filter_input(INPUT_POST, $name) ?: '';
    }
}
