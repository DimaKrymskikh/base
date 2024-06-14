<?php

use Base\Support\DB\DBconnection;
use PHPUnit\Framework\TestCase;

class DBconnectionTest extends TestCase
{
    public function test_bd_connection()
    {
        $db = require __DIR__.'/../../../config/db.php';
        $dbConnection = new DBconnection($db);
        
        $this->assertInstanceOf(DBconnection::class, $dbConnection);
        $this->assertInstanceOf(\PDO::class, $dbConnection->dbh);
    }
}
