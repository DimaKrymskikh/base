<?php

namespace Base\ValueObjects\Pagination;

use Base\Pagination\Paginator;

/**
 * Хранит целое число - (максимальное) число элементов на странице пагинации,
 * содержится в массиве Paginator::PAGINATOR_PER_PAGE_LIST.
 * Если входное значение не проходит валидацию, 
 * сохраняется дефолтное значение Paginator::PAGINATOR_DEFAULT_PER_PAGE.
 */
readonly class PerPageValue
{
    public int $value;
    
    private function __construct(string|null $perPage)
    {
        $intPerPage = intval($perPage);
        
        if (in_array($intPerPage, Paginator::PAGINATOR_PER_PAGE_LIST, true)) {
            $this->value = $intPerPage;
            return ;
        }
        
        $this->value = Paginator::PAGINATOR_DEFAULT_PER_PAGE;
    }
    
    public static function create(string|null $perPage): self
    {
        return new self($perPage);
    }
}
