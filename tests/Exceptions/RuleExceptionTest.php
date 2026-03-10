<?php

use Base\Exceptions\RuleException;
use Base\Server\ServerRequestInterface;
use Base\Session\ErrorsSession;
use PHPUnit\Framework\TestCase;

class RuleExceptionTest extends TestCase
{
    private ErrorsSession $errorsSession;
    private ServerRequestInterface $request;

    public function test_render(): void
    {
        $attr = 'test';
        $message = 'Некоторое сообщение';
        
        $this->request->expects($this->once())
                ->method('back');
        
        (new RuleException($attr, $message))->render($this->request);
        
        $this->assertEquals([$attr => $message], $this->errorsSession->getErrors());
    }
    
    #[\Override]
    protected function setUp(): void
    {
        $this->errorsSession = ErrorsSession::getInstance();
        $this->request = $this->createMock(ServerRequestInterface::class);
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        $this->errorsSession->destroy();
    }
}
