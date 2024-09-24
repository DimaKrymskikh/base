<?php

use Base\Services\ClockService;
use PHPUnit\Framework\TestCase;

class ClockServiceTest extends TestCase
{
    public function test_method_now_return_DateTimeImmutable_type(): void
    {
        $this->assertInstanceOf(\DateTimeImmutable::class, (new ClockService())->now());
    }
}
