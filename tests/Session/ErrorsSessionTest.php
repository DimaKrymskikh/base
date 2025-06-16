<?php

use Base\Session\ErrorsSession;
use PHPUnit\Framework\TestCase;

class ErrorsSessionTest extends TestCase
{
    private ErrorsSession $errorsSession;

    public function test_singleton(): void
    {
        $errorsSession = ErrorsSession::getInstance();
        $this->assertTrue($errorsSession === $this->errorsSession);
    }

    public function test_empty_errors(): void
    {
        $this->assertEquals([], $this->errorsSession->getErrors());
        $this->assertTrue($this->errorsSession->isEmpty());
    }

    public function test_non_empty_errors(): void
    {
        $this->errorsSession->setErrorMessage('one', 'errorOne');
        $this->errorsSession->setErrorMessage('two', 'errorTwo');
        
        $this->assertFalse($this->errorsSession->isEmpty());
        $this->assertEqualsCanonicalizing([
            'one' => 'errorOne',
            'two' => 'errorTwo'
        ], $this->errorsSession->getErrors());
        
        $this->assertTrue($this->errorsSession->isEmpty());
        $this->assertEquals([], $this->errorsSession->getErrors());
    }

    protected function setUp(): void
    {
        $this->errorsSession = ErrorsSession::getInstance();
    }
    
    protected function tearDown(): void
    {
        $this->errorsSession->destroy();
    }
}
