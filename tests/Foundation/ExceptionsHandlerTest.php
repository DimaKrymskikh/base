<?php

use Base\Exceptions\RuleException;
use Base\Foundation\ExceptionsHandler;
use Base\Session\ErrorsSession;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ExceptionsHandlerTest extends TestCase
{
    private ExceptionsHandler $handler;
    private LoggerInterface $loggerService;
    private ErrorsSession $errors;

    public function test_success_handle_for_html_exception(): void
    {
        $this->handler = new ExceptionsHandler($this->loggerService, false);
        
        $e = new RuleException('attr', 'Некоторое сообщение');
        $this->handler->handle($e);
        
        $this->assertEquals(['attr' => 'Некоторое сообщение'], $this->errors->getErrors());
    }
    
    public function test_success_handle_app_debug_true(): void
    {
        $this->handler = new ExceptionsHandler($this->loggerService, true);
        
        $e = new \Exception('Некоторое сообщение');
        
        $this->loggerService->expects($this->never())
                ->method('error');
        
        ob_start();
        $this->handler->handle($e);
        ob_end_clean();
        
        $this->assertEquals([], $this->errors->getErrors());
    }
    
    public function test_success_handle_app_debug_false(): void
    {
        $this->handler = new ExceptionsHandler($this->loggerService, false);
        
        $e = new \Exception('Некоторое сообщение');
        
        $this->loggerService->expects($this->once())
                ->method('error');
        
        $this->handler->handle($e);
        
        $this->assertEquals([], $this->errors->getErrors());
    }
    
    #[\Override]
    protected function setUp(): void
    {
        $this->errors = ErrorsSession::getInstance();
        $this->loggerService = $this->createMock(LoggerInterface::class);
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        $this->errors->destroy();
    }
}
