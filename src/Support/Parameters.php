<?php

namespace Base\Support;

use Base\Server\FilterRequestInterface;
use Base\Server\ServerRequestInterface;
use Base\Services\ArgumentsService;
use Base\Support\DB\DB;

/**
 * Родительский класс для классов, в которых нужно задавать зависимости контроллера и его экшенов.
 */
abstract class Parameters
{
    protected DB $db;
    protected object $config;
    protected FilterRequestInterface & ServerRequestInterface $serverRequest;

    public function __construct(
        private ArgumentsService $service,
        private string $action,
    ) {
        $this->db = $service->container->get('db');
        $this->config = $service->container->get('config');
        $this->serverRequest = $service->container->get('serverRequest');
        
        $this->service->setCtrlArgs($this->getCtrlArgs());
        $this->service->setActionArgs($this->getActionArgs($this->action));
    }
    
    /**
     * В этом методе должны быть описаны зависимости контроллера, если они есть.
     * 
     * @return array
     */
    protected function getCtrlArgs(): array
    {
        return [];
    }
    
    /**
     * В этом методе должны быть описаны зависимости экшенов, если они есть.
     */
    protected abstract function getActionArgs(string $action): array;
}
