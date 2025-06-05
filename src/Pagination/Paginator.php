<?php

namespace Base\Pagination;

use Base\Server\ServerRequestInterface;
use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;

final class Paginator implements PaginatorInterface
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

    public function __construct(
        private object $config,
        private ServerRequestInterface $serverRequest
    ) {
    }
    
    /**
     * Задаёт параметры пагинации.
     * 
     * @param PageValue $page
     * @param PerPageValue $perPage
     * @param int $total
     * @return void
     */
    public function setOptions(PageValue $page, PerPageValue $perPage, int $total): void
    {
        $this->total = $total;
        $this->perPage = $perPage->value;
        $this->pagesNumber = (int) ceil($total / $perPage->value);
        $this->currentPage = min($page->value, $this->pagesNumber);
        $this->offset = ($this->currentPage - 1) * $perPage->value;
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
    
    /**
     * Отрисовывает ссылку или текст, например, в ряде кнопок пагинации.
     * 
     * @param int $page - страница пагинации.
     * @param int $perPage - число элементов на странице.
     * @param array $fields - дополнительные поля запроса, например, фильтры по названию и описанию фильма
     * @param bool $isCurrentPage - если true, то возвращается текст, иначе - ссылка. (В ряде кнопок пагинации для текущей страницы задаётся текст.)
     * @param string $char - текст ссылки. Если $char = null, отрисовывается номер страницы.
     * @return string
     */
    public function link(int $page, int $perPage, array $fields = [], bool $isCurrentPage = false, string|null $char = null): string
    {
        $letter = $char ?? $page;
        
        $uri = "/{$this->serverRequest->getUri()}?page=$page&per_page=$perPage";
        
        foreach ($fields as $name => $value) {
            $uri .= "&$name=$value";
        }
        
        return $isCurrentPage ?
                <<<HTML
                    <span> $letter </span>
                HTML
                :
                <<<HTML
                    <a href="$uri"> $letter </a>
                HTML;
    }
    
    /**
     * Отрисовывает ряд кнопок пагинации.
     * 
     * @param array $fields - дополнительные поля запроса
     * @return string
     */
    public function links(array $fields = []): string
    {
        ob_start();
        extract($fields, EXTR_OVERWRITE);
        require_once $this->config->app_url.$this->config->pagination->view;
        return ob_get_clean();
    }
}
