<?php

use Base\Support\Filter\FilterRequest;
use PHPUnit\Framework\TestCase;

class FilterRequestTest extends TestCase
{
    private FilterRequest $filter;
    
    public function test_getInstance_method_create_a_single_object(): void
    {
        $filter = FilterRequest::getInstance();
        
        $this->assertInstanceOf(FilterRequest::class, $filter);
        $this->assertSame($this->filter, $filter);
    }
    
    protected function setUp(): void
    {
        $this->filter = FilterRequest::getInstance();
    }
}
