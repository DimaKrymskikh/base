<?php

namespace Base\Foundation;

use Base\DataTransferObjects\InputServerDto;
use Base\Support\DB\DB;
use Base\Support\Options;
use Base\Support\Request;

final class Application
{
    // Контейнер приложения
    private array $storage = [];

    public function __construct(DB $db, object $config, InputServerDto $inputServer)
    {
        $this->bind('db', fn (): DB => $db);
        
        // Если в конфигурации приложения не заданы некоторые параметры, берём дефолтные
        $finishedConfig = (new Options($config))->config;
        $this->bind('config', fn (): object => $finishedConfig);
        
        // Определяем настройки, соответствующие запросу
        $this->bind('request', fn (): object => (new Request($finishedConfig, $inputServer))->request);
    }
    
    /**
     * Добавляет объект в контейнер приложения
     * 
     * @param string $key - ключ объекта в контейнере
     * @param \Closure $callback - замыкание, которое должно возвращать объект
     * @return void
     */
    public function bind(string $key, \Closure $callback): void
    {
        $this->storage[$key] = $callback();
    }
    
    /**
     * Извлекает из контейнера приложения объект с ключом $key
     * 
     * @param string $key
     * @return mixed
     */
    public function make(string $key): mixed
    {
        return isset($this->storage[$key]) ? $this->storage[$key] : null;
    }
}
