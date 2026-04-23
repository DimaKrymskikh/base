<?php

namespace Tests\Support\DB;

use Base\Container\Container;
use Base\Support\DB\DB;
use Base\Support\DB\DBhandle;
use PHPUnit\Framework\TestCase;

abstract class DBCase extends TestCase
{
    protected Container $container;
    protected DBhandle $dbHandle;
    protected DB $db;
    
    #[\Override]
    protected function setUp(): void
    {
        $db = require __DIR__.'/../../../config/db.php';
        $config = (object) [];
        $config->db = $db;
        
        $this->container = Container::getInstance();
        $this->container->set('config', $config);
        
        $this->dbHandle = DBhandle::getInstance();
        
        $this->db = new DB($this->dbHandle);
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        $this->container->flush();
    }
}
