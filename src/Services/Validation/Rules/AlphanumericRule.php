<?php

namespace Base\Services\Validation\Rules;

final class AlphanumericRule
{
    /**
     * Проверяет величину $field на соответствие правилу alphanumeric (состоит из букв и чисел).
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field): bool
    {
        return ctype_alnum($field);
    }
}
