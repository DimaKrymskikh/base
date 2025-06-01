<?php

use PHPUnit\Framework\TestCase;

use Base\Utils\ArrayUtils;

class ArrayUtilsTest extends TestCase
{
    public function test_flatToComplex(): void
    {
        $flatNoRepeats = [
                (object)[
                    'id' => 1,
                    'name' => 'aaa',
                    'description' => 'qqqqq'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'bbb',
                    'description' => 'zzzzz'
                ]
            ];

        $flatWithRepeats = [
                (object)[
                    'id' => 1,
                    'name' => 'aaa',
                    'description' => 'qqqqq',
                    'items' => 's'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => 's'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => 't'
                ]
        ];

        $flatWithRepeatsD2 = [
                (object)[
                    'id' => 1,
                    'name' => 'aaa',
                    'description' => 'qqqqq',
                    'items' => 's',
                    'foo' => 10
                ],
                (object)[
                    'id' => 2,
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => 's',
                    'foo' => 20
                ],
                (object)[
                    'id' => 2,
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => 't',
                    'foo' => 20
                ]
        ];

        $complexNoRepeats = [
            1 => (object)[
                    'name' => 'aaa',
                    'description' => 'qqqqq'
                ],
            2 => (object)[
                    'name' => 'bbb',
                    'description' => 'zzzzz'
                ],
        ];

        $complexWithRepeats = [
            1 => (object)[
                    'name' => 'aaa',
                    'description' => 'qqqqq',
                    'items' => ['s']
                ],
            2 => (object)[
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => ['s', 't']
                ],
        ];

        $complexWithRepeatsD2 = [
            1 => (object)[
                    'name' => 'aaa',
                    'description' => 'qqqqq',
                    'items' => ['s'],
                    'foo' => [10]
                ],
            2 => (object)[
                    'name' => 'bbb',
                    'description' => 'zzzzz',
                    'items' => ['s', 't'],
                    'foo' => [20, 20]
                ],
        ];

        $this->assertEquals($complexNoRepeats, ArrayUtils::flatToComplex($flatNoRepeats, 'id', ['name', 'description']));
        $this->assertEquals($complexWithRepeats, ArrayUtils::flatToComplex($flatWithRepeats, 'id', ['name', 'description'], ['items']));
        $this->assertEquals($complexWithRepeatsD2, ArrayUtils::flatToComplex($flatWithRepeatsD2, 'id', ['name', 'description'], ['items', 'foo']));
    }
    
    public function test_getArrayAsString(): void
    {
        $arr = [
            'a' => 1,
            'b' => 'test'
        ];
        
        $this->assertEquals("[a]: 1 \n[b]: test \n", ArrayUtils::getArrayAsString($arr));
    }
    
    public function test_getArrayAsString_empty_array(): void
    {
        $arr = [];
        
        $this->assertEquals('', ArrayUtils::getArrayAsString($arr));
    }
}
