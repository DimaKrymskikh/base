<?php

namespace Base\Services\Validation\Rules;

final class BetweenRule
{
    /**
     * Проверяет величину $field на соответствие правилу between: min, max.
     * 
     * @param string $field
     * @param int $min
     * @param int $max
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field, int $min, int $max): bool
    {
        $len = mb_strlen($field);
        return $len >= $min && $len <= $max;
    }
}
