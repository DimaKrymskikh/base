<?php

namespace Base\Pagination;

/**
 * Класс хранящий представления для отрисовки пагинации.
 */
final class PaginationViews
{
    private readonly string $uri; // Базовый uri пагинации.
    private Paginator $paginator;
    
    public function __construct(string $uri, Paginator $paginator)
    {
        $this->uri = mb_trim($uri, '/');
        $this->paginator = $paginator;
    }
    
    /**
     * Отрисовывает ссылку с параметрами пагинации.
     * 
     * @param int $page Страница пагинации.
     * @param int $perPage Число элементов на странице.
     * @param array $fields Дополнительные поля ссылки, например, фильтры.
     * @param string|null $char Текст ссылки. Если $char = null, отрисовывается номер страницы.
     * @return string
     */
    public function link(int $page, int $perPage, array $fields = [], string|null $char = null): string
    {
        $letter = $char ?? $page;
        
        $uri = "/$this->uri?page=$page&per_page=$perPage";
        
        array_walk($fields, function($value, $name) use (&$uri) {
            $uri .= "&$name=$value";
        });
        
        return <<<HTML
                    <a href="$uri"> $letter </a>
                HTML;
    }
    
    /**
     * Отрисовывает тег <span> с номером или символом в ряду пагинации.
     * 
     * @param string|int $letter
     * @return string
     */
    public function span(string|int $letter): string
    {
        return <<<HTML
                    <span> $letter </span>
                HTML;
    }
    
    /**
     * Отрисовывает ряд кнопок пагинации.
     * 
     * @param string $file Полный путь до файла с кнопками пагинации.
     * @param array $fields Дополнительные поля ссылки, например, фильтры.
     * @return string
     */
    public function links(string $file, array $fields = []): string
    {
        ob_start();
        extract($fields, EXTR_OVERWRITE);
        
        $appUrl = mb_trim(config('app_url'), '/');
        $paginationFolder = mb_trim(config('pagination')->folder, '/');
        
        require_once $appUrl.'/'.$paginationFolder.'/'.$file;
        
        return ob_get_clean();
    }
}
