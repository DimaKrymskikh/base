<?php

namespace Base\Services\Validation\Rules;

final class EmailRule
{
    /**
     * Проверяет величину $field на соответствие правилу email.
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field): bool
    {
        return filter_var($field, FILTER_VALIDATE_EMAIL);
    }
}
