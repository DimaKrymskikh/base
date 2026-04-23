<?php

namespace Tests\Support\DB;

use Base\Support\DB\DBhandle;

class DBhandleTest extends DBCase
{
    public function test_singleton(): void
    {
        $dbHandle = DBhandle::getInstance();
        $this->assertTrue($dbHandle === $this->dbHandle);
    }
}
