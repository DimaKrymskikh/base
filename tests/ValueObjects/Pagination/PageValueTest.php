<?php

use Base\Pagination\Paginator;
use Base\ValueObjects\Pagination\PageValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PageValueTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            [12, '12'],
            [15, ' 15 '],
            [25, '+25'],
        ];
    }
    
    #[DataProvider('successProvider')]
    public function test_success_create(int $page, string $str): void
    {
        $this->assertEquals($page, PageValue::create($str)->value);
    }
    
    public static function inCorrectPagesProvider(): array
    {
        return [
            [null],
            [''],
            [' '],
            ['0'],
            ['+'],
            ['+0'],
            ['-7'],
            ['-1.3'],
            ['x7'],
            ['aaa'],
            ['+a'],
            ['77777777777777777777777777777777777777777777777777777'],
            ['-77777777777777777777777777777777777777777777777777777'],
            ['77777777777777777777777777777777777777777777777777777.1'],
            ['-15.77777777777777777777777777777777777777777777777777777'],
        ];
    }
    
    #[DataProvider('inCorrectPagesProvider')]
    public function test_incorrect_pages(string|null $str): void
    {
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE, PageValue::create($str)->value);
    }
}
