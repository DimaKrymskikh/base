<?php

namespace Base\ValueObjects\Pagination;

use Base\Pagination\Paginator;

/**
 * Хранит целое число, пригодное для задания страницы пагинации.
 * Если входное значение не проходит валидацию, 
 * сохраняется дефолтное значение Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE
 */
readonly class PageValue
{
    public int $value;
    
    private function __construct(string|null $page)
    {
        $intPage = intval($page);
        
        // Номер страницы должен быть положительным
        if ($intPage <= 0 || $intPage === PHP_INT_MAX) {
            $this->value = Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE;
            return ;
        } else {
            $this->value = $intPage;
        }
    }
    
    public static function create(string|null $page): self
    {
        return new self($page);
    }
}
