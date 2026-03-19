<?php

namespace Base\Pagination;

use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;

/**
 * Класс хранящий представления для отрисовки пагинации.
 */
final class PaginationViews
{
    private Paginator $paginator;
    
    public function __construct(
            private readonly string $uri // Базовый uri пагинации.
    ) {
        $this->paginator = new Paginator();
    }
    
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
        $this->paginator->setOptions($page, $perPage, $total);
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
        
        foreach ($fields as $name => $value) {
            $uri .= "&$name=$value";
        }
        
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
        require_once $file;
        return ob_get_clean();
    }
}
