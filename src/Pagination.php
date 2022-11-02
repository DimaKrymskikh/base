<?php

namespace Base;

/**
 * Класс реализующий пагинацию
 * В пагинации должно быть нечётное число кнопок
 * @property int $activePage - номер активной страницы документа
 * @property int $itemsNumberOnPage - планируемое число элементов на странице
 * @property int $pagesNumber - число страниц документа
 * @property int $firstButton - первая кнопка пагинации
 * @property int $lastButton - последняя кнопка пагинации
 */
class Pagination 
{
    // Дефолтное значение номера активной страницы
    const DEFAULT_ACTIVE_PAGE = 1;
    // Дефолтное значение числа эдементов на странице
    const DEFAULT_ITEMS_NUMBER_ON_PAGE = 20;
    // Дефолтное значение числа кнопок пагинации
    const DEFAULT_BUTTONS_NUMBER = 5;
    
    private int $activePage;
    private int $itemsNumberOnPage;
    private int $pagesNumber;
    private int $firstButton;
    private int $lastButton;

    /**
     * Задаются свойства пагинации
     * @param int $activePage - номер активной страницы документа
     * @param int $itemsNumberOnPage - число эдементов на странице
     * @param int $itemsNumberTotal - число эдементов во всём документе
     * @param int $buttonsNumber - число кнопок пагинации
     * @throws PrintException - выбрасывается при чётном числе кнопок пагинации
     */
    public function __construct(int $activePage, int $itemsNumberOnPage, int $itemsNumberTotal, int $buttonsNumber = self::DEFAULT_BUTTONS_NUMBER) 
    {
        // Задаём номер активной страницы документа
        $this->activePage = $itemsNumberTotal ? $activePage : 0;
        // Число эдементов на странице
        $this->itemsNumberOnPage = $itemsNumberOnPage;
        // Находим число страниц документа
        $this->pagesNumber = intdiv($itemsNumberTotal, $itemsNumberOnPage) + ($itemsNumberTotal % $itemsNumberOnPage ? 1 : 0);
        // Находим первую кнопку пагинации
        $this->firstButton = $itemsNumberTotal ? max(1, $activePage - intdiv($buttonsNumber, 2)) : 0;
        // Находим последнюю кнопку пагинации
        $this->lastButton = $itemsNumberTotal ? min($this->pagesNumber, $activePage + intdiv($buttonsNumber, 2)) : 0;
    }
    
    /**
     * Возвращает номер первого элемента активной страницы документа
     * @param int $activePage - номер активной страницы
     * @param int $itemsNumberOnPage - число элементов на странице
     * @return int
     */
    public static function from(int $activePage, int $itemsNumberOnPage): int
    {
        return ($activePage - 1) * $itemsNumberOnPage + 1;
    }
    
    /**
     * Возвращает номер последнего элемента активной страницы документа
     * (Номер последнего элемента активной страницы может быть больше обшего числа элементов)
     * @param int $activePage - номер активной страницы
     * @param int $itemsNumberOnPage - число элементов на странице
     * @return int
     */
    public static function to(int $activePage, int $itemsNumberOnPage): int
    {
        return $activePage * $itemsNumberOnPage;
    }
    
    /**
     * Возвращает число элементов на активной странице
     * На последней странице число элементов может быть меньше $itemsNumberOnPage
     * @param int $activePage - номер активной страницы
     * @param int $itemsNumberOnPage - запланированное число элементов на странице
     * @param int $itemsNumberTotal - число эдементов во всём документе
     * @return int
     */
    public static function getElementsNumberOnActivePage(int $activePage, int $itemsNumberOnPage, int $itemsNumberTotal): int
    {
        return min($itemsNumberOnPage, $itemsNumberTotal - ($activePage - 1) * $itemsNumberOnPage);
    }
    
    /**
     * Возвращает свойства класса Pagination, собранные в object
     * @return object
     */
    public function get(): object
    {
        return (object)[
            'activePage' => $this->activePage,
            'itemsNumberOnPage' => $this->itemsNumberOnPage,
            'pagesNumber' => $this->pagesNumber,
            'firstButton' => $this->firstButton,
            'lastButton' => $this->lastButton
        ];
    }
}
