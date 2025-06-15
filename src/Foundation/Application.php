<?php

namespace Base\Foundation;

use Base\Container\Container;
use Base\Server\CsrfProtection;
use Base\Server\ServerRequest;
use Base\Services\ClockService;
use Base\Services\FileService;
use Base\Services\LoggerService;
use Base\Session\ErrorsSession;
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
        $this->container = new Container();
        
        $serverRequest = new ServerRequest();
        (new CsrfProtection($serverRequest))->check();
        $this->container->set('serverRequest', $serverRequest);
        
        $this->container->set('db', new DB( new DBconnection($config->db) ));

        $finishedConfig = (new Options($config))->config;
        $this->container->set('config', $finishedConfig);

        $this->container->set('requestModule', (new RequestModule($finishedConfig, $serverRequest))->request);
        
        $loggerService = new LoggerService($finishedConfig->logs, new ClockService(), new FileService());
        $this->container->set('loggerService', $loggerService);
        
        set_exception_handler([new HandleExceptions($loggerService), 'render']);
    }
    
    public function getContainer(): Container
    {
        return $this->container;
    }
    
    public function withAssetsLogs(): void
    {
        $loggerService = $this->container->get('loggerService');
        $serverRequest = $this->container->get('serverRequest');
        
        $loggerService->info($serverRequest->getGlobalArraysAsString());
    }
}
