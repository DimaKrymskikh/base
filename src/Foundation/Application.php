<?php

namespace Base\Foundation;

use Base\Container\Container;
use Base\Server\CsrfProtection;
use Base\Server\ServerRequest;
use Base\Services\ClockService;
use Base\Services\FileService;
use Base\Services\LoggerService;
use Base\Support\DB\DB;
use Base\Support\DB\DBhandle;
use Base\Support\Options;
use Base\Support\RequestModule;

final class Application
{
    // Контейнер приложения
    private Container $container;

    public function __construct(object $config)
    {
        $this->container = Container::getInstance();
        
        $serverRequest = new ServerRequest();
        $this->container->set('serverRequest', $serverRequest);

        $finishedConfig = (new Options($config))->config;
        $this->container->set('config', $finishedConfig);

        $this->container->set('requestModule', (new RequestModule($finishedConfig, $serverRequest))->request);
        
        $loggerService = new LoggerService($finishedConfig->logs, new ClockService(), new FileService());
        $this->container->set('loggerService', $loggerService);
        
        // Записываем внешние данные.
        $loggerService->info($serverRequest->getGlobalArraysAsString());
        
        set_exception_handler([new ExceptionsHandler($loggerService, $serverRequest), 'handle']);
        
        (new CsrfProtection($serverRequest))->check();
        
        $this->container->set('db', new DB(DBhandle::getInstance()));
    }
}
