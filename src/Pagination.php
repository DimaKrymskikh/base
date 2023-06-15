<?php

namespace Base;

/**
 * Класс реализующий пагинацию
 * В пагинации должно быть нечётное число кнопок
 */
class Pagination
{
    // Дефолтное значение номера активной страницы
    public const DEFAULT_ACTIVE_PAGE = 1;
    // Дефолтное значение числа элементов на странице
    public const DEFAULT_ITEMS_NUMBER_ON_PAGE = 20;
    // Дефолтное значение числа кнопок пагинации
    public const DEFAULT_BUTTONS_NUMBER = 5;

    // Номер активной страницы документа
    private int $activePage;
    // Планируемое число элементов на странице
    private int $itemsNumberOnPage;
    // Общее число элементов документа, к которому применяется пагинация
    private int $itemsNumberTotal;
    // Число страниц документа
    private int $pagesNumber;
    // Первая кнопка пагинации
    private int $firstButton;
    // Последняя кнопка пагинации
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
        // Общее число элементов
        $this->itemsNumberTotal = $itemsNumberTotal;
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
     * @return int
     */
    private function getElementsNumberOnActivePage(): int
    {
        return $this->itemsNumberTotal ? min($this->itemsNumberOnPage, $this->itemsNumberTotal - ($this->activePage - 1) * $this->itemsNumberOnPage) : 0;
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
            'itemsNumberTotal' => $this->itemsNumberTotal,
            'pagesNumber' => $this->pagesNumber,
            'firstButton' => $this->firstButton,
            'lastButton' => $this->lastButton,
            'elementsNumberOnActivePage' => $this->getElementsNumberOnActivePage($this->activePage, $this->itemsNumberOnPage, $this->itemsNumberTotal)
        ];
    }
}
