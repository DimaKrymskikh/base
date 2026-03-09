<?php

namespace Base\Services\Validation\Rules;

/**
 * Проверка на принадлежность целым числам.
 */
final class IntegerRule
{
    /**
     * Проверяет, что величина $field является целым числом.
     * Если заданы параметры $min и $max, то проверяется условие $min <= $field <= $max.
     * Если задан только один параметр ($min или $max), то величина $field не пройдёт проверку.
     * 
     * @param string|int $field
     * @param int|null $min
     * @param int|null $max
     * @return bool - true, если правило выполнено.
     */
    public static function check(string|int $field, int|null $min = null, int|null $max = null): bool
    {
        $options = [];
        
        if(func_num_args() === 2) {
            return false;
        }
        
        if(func_num_args() === 3) {
            $options['options'] = [
                    'min_range' => $min,
                    'max_range' => $max,
                ];
        }
        
        return filter_var($field, FILTER_VALIDATE_INT, $options) !== false;
    }
}
