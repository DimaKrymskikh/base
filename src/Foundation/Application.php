<?php

namespace Base\Foundation;

use Base\Container\Container;
use Base\Server\CsrfProtection;
use Base\Server\ServerRequest;
use Base\Services\ClockService;
use Base\Services\FileService;
use Base\Services\LoggerService;
use Base\Support\DB\DB;
use Base\Support\DB\DBconnection;
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
        (new CsrfProtection($serverRequest))->check();
        $this->container->set('serverRequest', $serverRequest);
        
        $db = new DB(new DBconnection($config->db));
        $this->container->set('db', $db);

        $finishedConfig = (new Options($config))->config;
        $this->container->set('config', $finishedConfig);

        $this->container->set('requestModule', (new RequestModule($finishedConfig, $serverRequest))->request);
        
        $loggerService = new LoggerService($finishedConfig->logs, new ClockService(), new FileService());
        $this->container->set('loggerService', $loggerService);
        
        set_exception_handler([new ExceptionsHandler($loggerService, $serverRequest, $db), 'handle']);
    }
    
    public function withAssetsLogs(): void
    {
        $loggerService = $this->container->get('loggerService');
        $serverRequest = $this->container->get('serverRequest');
        
        $loggerService->info($serverRequest->getGlobalArraysAsString());
    }
}
