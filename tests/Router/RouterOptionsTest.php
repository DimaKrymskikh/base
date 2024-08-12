<?php

use Base\Router\RouterOptions;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RouterOptionsTest extends TestCase
{
    public static function patternProvider(): array
    {
        return [
            ['/', ['']],
            ['foo', ['foo']],
            ['/foo/{a}', ['foo', '{a}']],
            ['foo/{a}/str/{b}', ['foo', '{a}', 'str', '{b}']],
        ];
    }
    
    #[DataProvider('patternProvider')]
    public function test_find_patternChunks($pattern, $patternChunks): void
    {
        $this->assertEquals($patternChunks, (new RouterOptions('get', $pattern, 'controller'))->patternChunks);
    }
}
