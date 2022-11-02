<?php

use PHPUnit\Framework\TestCase;

use Base\Pagination;
//use base\PrintException;

class PaginationTest extends TestCase
{
    /**
     * Проверка правильности свойств, определяемых конструктором
     */
    public function testConstruct()
    {
        // Конструктор с дефолтным параметром
        $pag1 = new Pagination(2, 10, 77);
        // Проверка номера активной страницы
        $this->assertEquals(2, $pag1->activePage);
        // Число страниц текста, когда деление с остатком
        $this->assertEquals(8, $pag1->pagesNumber);
        // Нахождение 1-й страницы
        $this->assertEquals(1, $pag1->firstButton);
        // Нахождение последней страницы
        $this->assertEquals(4, $pag1->lastButton);
        
        // Конструктор с 4 параметрами
        $pag2 = new Pagination(4, 20, 120, 7);
        // Проверка номера активной страницы
        $this->assertEquals(4, $pag2->activePage);
        // Число страниц текста, когда деление без остатка
        $this->assertEquals(6, $pag2->pagesNumber);
        // Нахождение 1-й страницы
        $this->assertEquals(1, $pag2->firstButton);
        // Нахождение последней страницы
        $this->assertEquals(6, $pag2->lastButton);

        // Пагинация для документа с одной страницей
        $pag3 = new Pagination(1, 20, 3);
        // Проверка номера активной страницы
        $this->assertEquals(1, $pag3->activePage);
        // Число страниц текста (число элементов на странице не больше числа элементов во всём документе)
        $this->assertEquals(1, $pag3->pagesNumber);
        // Нахождение 1-й страницы
        $this->assertEquals(1, $pag3->firstButton);
        // Нахождение последней страницы
        $this->assertEquals(1, $pag3->lastButton);

        // Пагинация для документа без элементов
        $pag4 = new Pagination(1, 20, 0);
        // Проверка номера активной страницы
        $this->assertEquals(0, $pag4->activePage);
        // Число страниц текста (число элементов на странице не больше числа элементов во всём документе)
        $this->assertEquals(0, $pag4->pagesNumber);
        // Нахождение 1-й страницы
        $this->assertEquals(0, $pag4->firstButton);
        // Нахождение последней страницы
        $this->assertEquals(0, $pag4->lastButton);
    }
    
    /**
     * Проверка нахождения первого и последнего элементов на странице документа
     */
    public function testElementsOnPage() 
    {
        // Первый элемент страницы
        $this->assertEquals(76, Pagination::from(4, 25));
        // Последний элемент страницы
        $this->assertEquals(100, Pagination::to(4, 25));
    }
    
    /**
     * Проверка нахождения числа элементов на актвной странице
     */
    public function testElementsNumberOnActivePage() 
    {
        // Если на одной странице может быть не более 20 элементов,
        // и общее число элементов равно 21, то
        // на первой странице будет 20 элементов,
        $this->assertEquals(20, Pagination::getElementsNumberOnActivePage(1, 20, 21));
        // а на второй 1 элемент.
        $this->assertEquals(1, Pagination::getElementsNumberOnActivePage(2, 20, 21));
    }
}
