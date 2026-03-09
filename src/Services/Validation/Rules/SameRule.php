<?php

namespace Base\Services\Validation\Rules;

final class SameRule
{
    /**
     * Проверяет, что строка $field равна строке $other.
     * 
     * @param string $field
     * @param string $other
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field, string $other): bool
    {
        return $field === $other;
    }
}
