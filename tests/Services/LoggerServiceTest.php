<?php

use Base\Services\LoggerService;
use Base\Contracts\File\FileInterface;
use Psr\Clock\ClockInterface;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class LoggerServiceTest extends TestCase
{
    private LoggerService $logger;
    private ClockInterface $clock;
    private FileInterface $file;

    public function test_info(): void
    {
        $this->file->expects($this->once())
                ->method('put');
        
        $this->logger->info('текст');
    }

    public function test_error(): void
    {
        $this->file->expects($this->once())
                ->method('put');
        
        $this->logger->error('текст');
    }

    protected function setUp(): void
    {
        $logs = Config::getConfigWithLogs()->logs;
        
        $this->clock = $this->createStub(ClockInterface::class);
        
        $this->file = $this->createMock(FileInterface::class);
        
        $this->logger = new LoggerService($logs, $this->clock, $this->file);
    }
}
