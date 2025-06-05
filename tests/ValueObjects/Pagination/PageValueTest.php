<?php

use Base\Pagination\Paginator;
use Base\ValueObjects\Pagination\PageValue;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class PageValueTest extends TestCase
{
    public static function correctPagesProvider(): array
    {
        return [
            [12, '12'],
            [15, ' 15 '],
            // Строка начинается с последовательности цифр
            [71, '71x'],
            [7, "\t 7 x"],
            [15, '15.25 '],
            [20, '2e1'],
            [25, '025'],
            [25, '+25'],
            [15, '15.77777777777777777777777777777777777777777777777777777'],
            [15, '+15.77777777777777777777777777777777777777777777777777777'],
        ];
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
            // Строка начинается с буквы
            ['x7'],
            ['aaa'],
            ['+a'],
            ['77777777777777777777777777777777777777777777777777777'],
            ['-77777777777777777777777777777777777777777777777777777'],
            ['77777777777777777777777777777777777777777777777777777.1'],
            ['-15.77777777777777777777777777777777777777777777777777777'],
        ];
    }
    
    #[DataProvider('correctPagesProvider')]
    public function test_correct_pages(int $page, string $str): void
    {
        $this->assertEquals($page, PageValue::create($str)->value);
    }
    
    #[DataProvider('inCorrectPagesProvider')]
    public function test_incorrect_pages(string|null $str): void
    {
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE, PageValue::create($str)->value);
    }
}
