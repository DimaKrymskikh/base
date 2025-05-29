<?php

use Base\Server\CsrfProtection;
use Base\Server\FilterRequestInterface;
use Base\Server\ServerRequestInterface;
use PHPUnit\Framework\TestCase;

class CsrfProtectionTest extends TestCase
{
    private FilterRequestInterface & ServerRequestInterface $serverRequest;

    public function test_check_method_get(): void
    {
        $this->serverRequest->method('getMethod')
                ->willReturn('GET');
        
        $this->assertNull( (new CsrfProtection($this->serverRequest))->check() );
    }

    public function test_success_check_method_post(): void
    {
        $this->serverRequest->method('getMethod')
                ->willReturn('POST');
        
        $this->serverRequest->method('filterInputPost')
                ->willReturn($_SESSION['csrf_token']);
        
        $this->assertNull( (new CsrfProtection($this->serverRequest))->check() );
    }

    public function test_fail_check_method_post(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('405 Method Not Allowed');
        
        $this->serverRequest->method('getMethod')
                ->willReturn('POST');
        
        $this->serverRequest->method('filterInputPost')
                ->willReturn('');
        
        $this->assertNull( (new CsrfProtection($this->serverRequest))->check() );
    }

    protected function setUp(): void
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        
        $this->serverRequest = $this->createStubForIntersectionOfInterfaces([FilterRequestInterface::class, ServerRequestInterface::class]);
    }
}
