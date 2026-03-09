<?php

use Base\Services\Validation\Rules\IntegerRule;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class IntegerRuleTest extends TestCase
{
    public static function successProviderForOneParameter(): array
    {
        return [
            ['-3'],
            [5],
            [0],
            ['0'],
        ];
    }
    
    #[DataProvider('successProviderForOneParameter')]
    public function test_success_check_one_parameter(string|int $field): void
    {
        $this->assertTrue(IntegerRule::check($field));
    }
    
    public static function failProviderForOneParameter(): array
    {
        return [
            ['xx'],
            ['-'],
        ];
    }
    
    #[DataProvider('failProviderForOneParameter')]
    public function test_fail_check_one_parameter(string|int $field): void
    {
        $this->assertFalse(IntegerRule::check($field));
    }
    
    public static function successProviderForThreeParameters(): array
    {
        return [
            ['3', 2, 5],
            [5, 2, 5],
        ];
    }
    
    #[DataProvider('successProviderForThreeParameters')]
    public function test_success_check_three_parameters(string|int $field, int $min, int $max): void
    {
        $this->assertTrue(IntegerRule::check($field, $min, $max));
    }
    
    public static function failProviderForThreeParameters(): array
    {
        return [
            ['1', 2, 5],
            [6, 2, 5],
        ];
    }
    
    #[DataProvider('failProviderForThreeParameters')]
    public function test_fail_check_three_parameters(string|int $field, int $min, int $max): void
    {
        $this->assertFalse(IntegerRule::check($field, $min, $max));
    }
    
    public static function providerForTowParameters(): array
    {
        return [
            ['-1', 2],
            [3, 2],
        ];
    }
    
    #[DataProvider('providerForTowParameters')]
    public function test_check_tow_parameters(string|int $field, int $min): void
    {
        $this->assertFalse(IntegerRule::check($field, $min));
        $this->assertFalse(IntegerRule::check($field, null, $min));
    }
}
