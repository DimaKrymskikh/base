<?php

namespace Base\Pagination;

use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;

/**
 * Класс управляющий пагинацией.
 */
final class Paginator
{
    public const PAGINATOR_DEFAULT_PER_PAGE = 20;
    public const PAGINATOR_DEFAULT_CURRENT_PAGE = 1;
    public const PAGINATOR_DEFAULT_TOTAL = 0;
    public const PAGINATOR_DEFAULT_PAGES_NUMBER = 0;
    public const PAGINATOR_DEFAULT_OFFSET = 0;
    public const PAGINATOR_PER_PAGE_LIST = [10, self::PAGINATOR_DEFAULT_PER_PAGE, 50, 100, 1000];

    // Число элементов на странице.
    private int $perPage;
    // Номер текущей страницы.
    private int $currentPage;
    // Общее число элементов в списке.
    private int $total;
    // Число страниц.
    private int $pagesNumber;
    // Сдвиг до первого элемента текущей страницы.
    private int $offset;

    /**
     * Задаёт параметры пагинации.
     * 
     * @param PageValue $page Номер текущей страницы, заданный в ссылке пагинации.
     * @param PerPageValue $perPage Число элементов на странице, заданное в ссылке пагинации.
     * @param int $total Общее число элементов в списке, должно быть вычислено перед применением метода.
     * @return void
     */
    public function setOptions(PageValue $page, PerPageValue $perPage, int $total): void
    {
        $this->total = $total;
        $this->perPage = $perPage->value;
        $this->pagesNumber = (int) ceil($total / $perPage->value);
        // При удалении элементов страниц может стать меньше, поэтому нужен min
        $this->currentPage = min($page->value, $this->pagesNumber);
        // При удалении элементов, например, удаляется единственный элемент, 
        // offset может стать отрицательным, поэтому нужен max
        $this->offset = max(self::PAGINATOR_DEFAULT_OFFSET, ($this->currentPage - 1) * $perPage->value);
    }
    
    public function getTotal(): int
    {
        return $this->total ?? self::PAGINATOR_DEFAULT_TOTAL;
    }
    
    public function getPagesNumber(): int
    {
        return $this->pagesNumber ?? self::PAGINATOR_DEFAULT_PAGES_NUMBER;
    }
    
    public function getPerPage(): int
    {
       return $this->perPage ?? self::PAGINATOR_DEFAULT_PER_PAGE; 
    }
    
    public function getCurrentPage(): int
    {
       return $this->currentPage ?? self::PAGINATOR_DEFAULT_CURRENT_PAGE; 
    }
    
    public function getOffset(): int
    {
        return $this->offset ?? self::PAGINATOR_DEFAULT_OFFSET;
    }
}
