<?php

namespace Tests\Utils;

use Base\Utils\ArrayUtils;

class ArrayUtilsTest extends ArrayUtilsCase
{
    public function test_getComplexArrayFromFlatArray(): void
    {
        [$flat, $complex] = $this->getFlatAndComplexArrays();
        
        $this->assertEquals($complex, ArrayUtils::getComplexArrayFromFlatArray($flat, 'id', 'refs', 'ref_id', ['status', 'info']));
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
    
    public function test_movingIdToArrayKey(): void
    {
        [$flat, $complex] = $this->getArraysForMovingIdToArrayKey();
        
        $this->assertEquals($complex, ArrayUtils::movingIdToArrayKey($flat, 'ref_id'));
    }
    
    public function test_joinTwoArraysById(): void
    {
        $field = 'items';
        [$results, $contents, $items] = $this->getArraysForJoinTwoArraysById($field);
        
        $this->assertEquals($results, ArrayUtils::joinTwoArraysById($contents, $items, 'id', $field));
    }
}
