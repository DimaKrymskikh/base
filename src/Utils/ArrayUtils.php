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
     * Результатом является плоский массив $items, в котором повторяются значения полей основной таблицы, а прикреплённая таблица даёт расхождения строк
     * Если указать в параметре $key индексированное поле основной таблицы, например, первичный ключ id, в массиве $singles - поля основной таблицы,
     * в массиве $multiples - поля прикреплённой таблицы, то функция выдаст массив объектов с ключами, которые определяет $key, и значениями - объектами,
     * которые состоят из скалярных свойств массива $singles и свойств-массивов из $multiples
     * 
     * @param array $items - Исходный плоский массив
     * @param string $key - Поле, которое задаст ключи нового массива
     * @param array $singles - Массив полей, которые станут скалярными в новом массиве
     * @param array $multiples - Массив полей, которые станут массивами в новом массиве
     * @return array
     */
    public static function flatToComplex(array $items, string $key, array $singles = [], array $multiples = []): array
    {
        // Задаём массив ключей без повторов
        $itemsKey = array_unique(array_column($items, $key));
        // Новый массив
        $newItems = [];
        foreach ($itemsKey as $itemKey) {
            foreach ($items as $item) {
                // Обрабатываем только те строки, которые содержат тот же ключ
                if ($itemKey !== $item->$key) {
                    continue;
                }
                // Только в первой строке с данным ключом
                if (!array_key_exists($itemKey, $newItems)) {
                    // создаём объект значений для данного ключа
                    $newItems[$itemKey] = (object)[];
                    // создаём свойства-массивы
                    foreach ($multiples as $multiple) {
                        $newItems[$itemKey]->$multiple = [];
                    }
                    // находим скалярные свойства
                    foreach ($singles as $single) {
                        $newItems[$itemKey]->$single = $item->$single;
                    }
                }
                // По совокупности всех строк формируем свойства-массивы
                foreach ($multiples as $multiple) {
                    $newItems[$itemKey]->$multiple[] = $item->$multiple;
                }
            }
        }

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
        $str = '';
        foreach ($arr as $key => $value) {
            $str .= "[$key]: $value \n";
        }
        
        return $str;
    }
}
