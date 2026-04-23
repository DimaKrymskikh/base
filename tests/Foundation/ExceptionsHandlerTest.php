<?php

namespace Tests\Foundation;

use Base\Container\Container;
use Base\Exceptions\HtmlExceptionInterface;
use Base\Foundation\ExceptionsHandler;
use Base\Server\ServerRequestInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ExceptionsHandlerTest extends TestCase
{
    private ExceptionsHandler $handler;
    private ServerRequestInterface $request;
    private LoggerInterface $logger;

    public function test_handle_with_HtmlExceptionInterface(): void
    {
        $e = $this->createMockForIntersectionOfInterfaces([\Throwable::class, HtmlExceptionInterface::class]);
        
        $e->expects($this->once())->method('render')
                ->with($this->identicalTo($this->request));
        
        $this->assertNull($this->handler->handle($e));
    }

    public function test_handle_with_debug_true(): void
    {
        $container = Container::getInstance();
        $ob = (object) [
            'app_debug' => true,
        ];
        $container->set('config', $ob);
        
        $e = $this->createMock(\Throwable::class);
        
        $this->logger->expects($this->never())->method('error');
        
        ob_start();
        $this->assertNull($this->handler->handle($e));
        ob_end_clean();
        
        $container->flush();
    }

    public function test_handle_with_debug_false(): void
    {
        $container = Container::getInstance();
        $ob = (object) [
            'app_debug' => false,
        ];
        $container->set('config', $ob);
        
        $e = $this->createMock(\Throwable::class);
        
        $this->logger->expects($this->once())->method('error');
        
        $this->assertNull($this->handler->handle($e));
        
        $container->flush();
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->request = $this->createStub(ServerRequestInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        
        $this->handler = new ExceptionsHandler($this->logger, $this->request);
    }
}
