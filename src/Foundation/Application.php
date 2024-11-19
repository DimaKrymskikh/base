<?php

namespace Base\Foundation;

use Base\Container\Container;
use Base\DataTransferObjects\InputServerDto;
use Base\Support\DB\DB;
use Base\Support\Options;
use Base\Support\Request;

final class Application
{
    // Контейнер приложения
    private Container $container;

    public function __construct(DB $db, object $config, InputServerDto $inputServer)
    {
        $this->container = new Container();
        
        $this->container->set('db', $db);
        $this->container->set('inputServer', $inputServer);
        
        // Если в конфигурации приложения не заданы некоторые параметры, берём дефолтные
        $finishedConfig = (new Options($config))->config;
        $this->container->set('config', $finishedConfig);
        
        // Определяем настройки, соответствующие запросу
        $this->container->set('request', (new Request($finishedConfig, $inputServer))->request);
    }
    
    public function getContainer(): Container
    {
        return $this->container;
    }
}
