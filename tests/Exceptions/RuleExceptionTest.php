<?php

use Base\Exceptions\RuleException;
use Base\Session\ErrorsSession;
use PHPUnit\Framework\TestCase;

class RuleExceptionTest extends TestCase
{
    private ErrorsSession $errorsSession;

    public function test_render(): void
    {
        $attr = 'test';
        $message = 'Некоторое сообщение';
        
        (new RuleException($attr, $message))->render();
        
        $this->assertEquals([$attr => $message], $this->errorsSession->getErrors());
    }
    
    #[\Override]
    protected function setUp(): void
    {
        $this->errorsSession = ErrorsSession::getInstance();
    }
    
    #[\Override]
    protected function tearDown(): void
    {
        $this->errorsSession->destroy();
    }
}
