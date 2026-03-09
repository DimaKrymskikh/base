<?php

namespace Base\Services\Validation;

class ValidationService implements ValidationServiceInterface
{
    /**
     * Проверяет, что величина $field удовлетворяет правилам $options.
     * Возвращает массив сообщений об ошибках.
     * Если ошибки не обнаружены, возвращается пустой массив.
     * 
     * @param string $field Величина, которую нужно проверить.
     * @param string $options Правила проверки, разделённые символом |. У правил могут быть параметры. Правило и параметры разделяются символом :. Параметры записываются через запятую.
     * @param array $messages Ассоциативный массив сообщений об ошибках. Ключ - имя правила, значение - сообщение об ошибке.
     * @return array Простой массив сообщений об ошибках.
     */
    #[\Override]
    public function validate(string $field, string $options, array $messages): array
    {
        $rules = $this->split($options, '|');
        
        $errors = [];
        
        foreach ($rules as $rule) {
            $params = [];
            if (mb_strpos($rule, ':')) {
                [$ruleName, $paramStr] = $this->split($rule, ':');
                $params = $this->split($paramStr, ',');
            } else {
                $ruleName = $rule;
            }
            
            if(!isset($messages[$ruleName])) {
                throw new \LogicException("Для правила $ruleName не задано сообщение");
            }
            
            $className = $this->getClassName($ruleName);
            
            $pass = $className::check($field, ...$params);
            if (!$pass) {
                $errors[] = sprintf($messages[$ruleName], $field, ...$params);
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
        return array_map('mb_trim', explode($separator, $str));
    }
    
    /**
     * Для правила $ruleName возвращает класс валидации.
     * 
     * @param string $ruleName
     * @return string
     * @throws \LogicException
     */
    private function getClassName(string $ruleName): string
    {
        $className = '\Base\Services\Validation\Rules\\'.$ruleName.'Rule';
        if(!class_exists($className)) {
            throw new \LogicException("Задано не существующее правило $ruleName");
        }
        
        return $className;
    }
}
