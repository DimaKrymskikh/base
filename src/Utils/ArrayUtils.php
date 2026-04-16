<?php

namespace Base\Utils;

/**
 * Содержит статические методы для работы с массивами объектов
 */
class ArrayUtils
{
    /**
     * Преобразует плоский массив объектов в сложный.
     * Например, из базы извлекаются данные основной таблицы и прикреплённой к ней таблицы с отношением один ко многим.
     * Результатом является плоский массив $items, в котором повторяются значения полей основной таблицы, а прикреплённая таблица даёт расхождения строк.
     * 
     * Функция возвращает сложный массив с ключами $itemKey.
     * В std-объектах отсутствуют поля $refKey и $multiples.
     * В std-объектах присутствует поле-массив $field. Данный массив состоит  из std-объектов с полями $refKey и $multiples.
     * (См. \Tests\Utils\ArrayUtilsCase::getFlatAndComplexArrays()).
     * 
     * @param array $items Исходный плоский массив со значениями std-объектов.
     * @param string $itemKey id основной таблицы.
     * @param string $field Поле-массив, который будет добавлен в плоский массив.
     * @param string $refKey id прикреплённой таблицы.
     * @param array $multiples Поля прикреплённой таблицы.
     * @return array
     */
    public static function getComplexArrayFromFlatArray(array $items, string $itemKey, string $field, string $refKey, array $multiples): array
    {
        $itemsKey = [];
        $newItems = [];
        
        array_map(function($item) use ($itemKey, $field, $refKey, $multiples, &$itemsKey, &$newItems) {
            $ref = (object) [];
            $ref->id = $item->$refKey;
            
            array_map(function($multiple) use ($item, $ref) {
                $ref->$multiple = $item->$multiple;
                unset($item->$multiple);
            }, $multiples);
            
            unset($item->$refKey);
            
            if(!in_array($item->$itemKey, $itemsKey)) {
                $item->$field = [];
                $item->$field[] = $ref;
                $itemsKey[] = $item->$itemKey;
                $newItems[$item->$itemKey] = $item;
            } else {
                $newItems[$item->$itemKey]->$field[] = $ref;
            }
        }, $items);
        
        return $newItems;
    }
    
    /**
     * Возвращает строку, содержащую ключи и значения массива
     * 
     * @param array $arr
     * @return string
     */
    public static function getArrayAsString(array $arr): string
    {
        array_walk($arr, fn(&$value, $key) => $value = "[$key]: $value \n");
        
        return array_reduce($arr, fn($str, $item) => $str .= $item, '');
    }
}
