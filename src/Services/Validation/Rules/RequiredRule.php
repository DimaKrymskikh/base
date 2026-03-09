<?php

namespace Base\Services\Validation\Rules;

final class RequiredRule
{
    /**
     * Проверяет величину $field на соответствие правилу required.
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field): bool
    {
        return $field !== '';
    }
}
