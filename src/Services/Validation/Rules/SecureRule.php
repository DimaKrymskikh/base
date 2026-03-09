<?php

namespace Base\Services\Validation\Rules;

final class SecureRule
{
    /**
     * Проверяет величину $field на соответствие правилу secure.
     * (Величина $field должна состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву,
     * одну строчную латинскую букву и один специальный символ без кириллицы.)
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    public static function check(string $field): bool
    {
        $pattern = "^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[^а-яёА-ЯЁ]{8,64}$";
        return mb_ereg($pattern, $field);
    }
}
