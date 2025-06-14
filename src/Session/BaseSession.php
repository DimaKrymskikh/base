<?php

namespace Base\Session;

abstract class BaseSession
{
    private static array $arrInstance = [];
    
    public static function getInstance(): static
    {
        if (!in_array(static::$instance, self::$arrInstance)) {
            static::$instance = new static();
            self::$arrInstance[] = static::$instance;
        }
        
        return static::$instance;
    }
    
    private function __construct()
    {}
    
    /**
     * Удаляет переменную сессии
     */
    abstract public function destroy(): void;
}
