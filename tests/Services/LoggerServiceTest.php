<?php

use Base\Services\LoggerService;
use Base\Contracts\File\FileInterface;
use Psr\Clock\ClockInterface;
use Psr\Log\LogLevel;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class LoggerServiceTest extends TestCase
{
    public function test(): void
    {
        $logs = Config::getConfigWithLogs()->logs;
        
        $clock = $this->createMock(ClockInterface::class);
        $clock->expects($this->once())
                ->method('now');
        
        $fileService = $this->createMock(FileInterface::class);
        $fileService->expects($this->once())
                ->method('put');
        
        $logger = new LoggerService($logs, $clock, $fileService);
        $logger->log(LogLevel::INFO, 'aaa');
    }
}
