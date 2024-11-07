<?php

namespace Base\Services\Validation;

class ValidationService implements ValidationServiceInterface
{
    /**
     * Проверяет, что величина $field удовлетворяет правилам $options.
     * Возвращает массив сообщений об ошибках.
     * Если ошибки не обнаружены, возвращается пустой массив.
     * 
     * @param string $field - величина, которую нужно проверить.
     * @param string $options - правила проверки, разделённые символом |. У правил могут быть параметры. Правило и параметры разделяются символом :. Параметры записываются через запятую.
     * @param array $messages - массив сообщений об ошибках. Ключ - имя правила, значение - сообщение об ошибке.
     * @return array
     */
    public function validate(string $field, string $options, array $messages): array
    {
        $rules = $this->split($options, '|');
        
        $ruleMessages = array_filter($messages, fn($message) =>  is_string($message));
        
        $errors = [];
        
        foreach ($rules as $rule) {
            $params = [];
            if (strpos($rule, ':')) {
                [$ruleName, $paramStr] = $this->split($rule, ':');
                $params = $this->split($paramStr, ',');
            } else {
                $ruleName = $rule;
            }
            
            $fn = 'is'.ucfirst($ruleName);
            if (method_exists($this, $fn)) {
                $pass = $this->$fn($field, ...$params);
                if (!$pass) {
                    $errors[] = sprintf($ruleMessages[$ruleName], $field, ...$params);
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Разбивает строку $str в массив по разделителю $separator.
     * У элементов массива удаляются пробелы по краям.
     * 
     * @param string $str
     * @param string $separator
     * @return array
     */
    private function split(string $str, string $separator): array
    {
        return array_map('trim', explode($separator, $str));
    }
    
    /**
     * Проверяет величину $field на соответствие правилу required.
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    private function isRequired(string $field): bool
    {
        return trim($field) !== '';
    }
    
    /**
     * Проверяет величину $field на соответствие правилу email.
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    private function isEmail(string $field): bool
    {
        if (empty($field)) {
            return true;
        }

        return filter_var($field, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Проверяет величину $field на соответствие правилу alphanumeric.
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    private function isAlphanumeric(string $field): bool
    {
        if (empty($field)) {
            return true;
        }

        return ctype_alnum($field);
    }
    
    /**
     * Проверяет величину $field на соответствие правилу between: min, max.
     * 
     * @param string $field
     * @param int $min
     * @param int $max
     * @return bool - true, если правило выполнено.
     */
    private function isBetween(string $field, int $min, int $max): bool
    {
        if (empty($field)) {
            return true;
        }

        $len = mb_strlen($field);
        return $len >= $min && $len <= $max;
    }
    
    /**
     * Проверяет величину $field на соответствие правилу secure.
     * (Величина $field должна состоять из 8 - 64 символов и содержать как минимум одну цифру, одну заглавную латинскую букву,
     * одну строчную латинскую букву и один специальный символ без кириллицы.)
     * 
     * @param string $field
     * @return bool - true, если правило выполнено.
     */
    private function isSecure(string $field): bool
    {
        if (empty($field)) {
            return true;
        }

        $pattern = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)[^а-яёА-ЯЁ]{8,64}$/";
        return preg_match($pattern, $field);
    }
    
    /**
     * Проверяет величину $field на соответствие правилу same: other.
     * 
     * @param string $field
     * @param string $other
     * @return bool - true, если правило выполнено.
     */
    private function isSame(string $field, string $other): bool
    {
        if (empty($field) && empty($other)) {
            return true;
        }

        return $field === $other;
    }
}