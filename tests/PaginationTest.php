<?php

use PHPUnit\Framework\TestCase;

use Base\Pagination;

class PaginationTest extends TestCase
{
    /**
     * Проверка правильности свойств, определяемых конструктором
     */
    public function testConstruct()
    {
        // Конструктор с дефолтным параметром
        $pag1 = (new Pagination(2, 10, 77))->get();
        // 2 - номер активной страницы
        $this->assertEquals(2, $pag1->activePage);
        // 10 - заданное число элементов на странице
        $this->assertEquals(10, $pag1->itemsNumberOnPage);
        // 77 - общее число элементов
        $this->assertEquals(77, $pag1->itemsNumberTotal);
        // 8 - число страниц текста (деление с остатком)
        $this->assertEquals(8, $pag1->pagesNumber);
        // 1 - номер страницы, на которую указывает первая кнопка пагинации
        $this->assertEquals(1, $pag1->firstButton);
        // 4 - номер страницы, на которую указывает последняя кнопка пагинации
        $this->assertEquals(4, $pag1->lastButton);
        // 10 - найденное число элементов на активной странице (может быть меньше itemsNumberOnPage)
        $this->assertEquals(10, $pag1->elementsNumberOnActivePage);

        // Конструктор с 4 параметрами
        $pag2 = (new Pagination(4, 20, 120, 7))->get();
        // 4 - номер активной страницы
        $this->assertEquals(4, $pag2->activePage);
        // 20 - заданное число элементов на странице
        $this->assertEquals(20, $pag2->itemsNumberOnPage);
        // 120 - общее число элементов
        $this->assertEquals(120, $pag2->itemsNumberTotal);
        // 6 - число страниц текста (деление без остатка)
        $this->assertEquals(6, $pag2->pagesNumber);
        // 1 - номер страницы, на которую указывает первая кнопка пагинации
        $this->assertEquals(1, $pag2->firstButton);
        // 6 - номер страницы, на которую указывает последняя кнопка пагинации
        $this->assertEquals(6, $pag2->lastButton);
        // 20 - найденное число элементов на активной странице (может быть меньше itemsNumberOnPage)
        $this->assertEquals(20, $pag2->elementsNumberOnActivePage);

        // Пагинация для документа с одной страницей
        $pag3 = (new Pagination(1, 20, 3))->get();
        // 1 - номер активной страницы
        $this->assertEquals(1, $pag3->activePage);
        // 20 - заданное число элементов на странице
        $this->assertEquals(20, $pag3->itemsNumberOnPage);
        // 3 - общее число элементов
        $this->assertEquals(3, $pag3->itemsNumberTotal);
        // 1 - число страниц текста
        $this->assertEquals(1, $pag3->pagesNumber);
        // 1 - номер страницы, на которую указывает первая кнопка пагинации
        $this->assertEquals(1, $pag3->firstButton);
        // 1 - номер страницы, на которую указывает последняя кнопка пагинации
        $this->assertEquals(1, $pag3->lastButton);
        // 3 - найденное число элементов на активной странице (может быть меньше itemsNumberOnPage)
        $this->assertEquals(3, $pag3->elementsNumberOnActivePage);

        // Пагинация для документа без элементов
        $pag4 = (new Pagination(1, 20, 0))->get();
        // 0 - номер активной страницы
        $this->assertEquals(0, $pag4->activePage);
        // 20 - заданное число элементов на странице
        $this->assertEquals(20, $pag4->itemsNumberOnPage);
        // 0 - общее число элементов
        $this->assertEquals(0, $pag4->itemsNumberTotal);
        // 0 - число страниц текста
        $this->assertEquals(0, $pag4->pagesNumber);
        // 0 - номер страницы, на которую указывает первая кнопка пагинации
        $this->assertEquals(0, $pag4->firstButton);
        // 0 - номер страницы, на которую указывает последняя кнопка пагинации
        $this->assertEquals(0, $pag4->lastButton);
        // 0 - найденное число элементов на активной странице (может быть меньше itemsNumberOnPage)
        $this->assertEquals(0, $pag4->elementsNumberOnActivePage);
    }

    /**
     * Проверка нахождения первого и последнего элементов на активной странице документа
     */
    public function testElementsOnPage()
    {
        // Первый элемент страницы
        $this->assertEquals(76, Pagination::from(4, 25));
        // Последний элемент страницы
        $this->assertEquals(100, Pagination::to(4, 25));
    }
}
