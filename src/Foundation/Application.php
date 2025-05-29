<?php

namespace Base\Foundation;

use Base\Container\Container;
use Base\Server\CsrfProtection;
use Base\Server\ServerRequest;
use Base\Support\DB\DB;
use Base\Support\DB\DBconnection;

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

        $finishedConfig = (new Options($this->config))->config;
        $this->container->set('config', $finishedConfig);

        $this->container->set('requestModule', (new RequestModule($finishedConfig, $serverRequest))->request);
    }
    
    public function getContainer(): Container
    {
        return $this->container;
    }
}
