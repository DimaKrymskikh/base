<?php

namespace Base\ValueObjects\Pagination;

use Base\Pagination\Paginator;
use Base\Services\Validation\Rules\IntegerRule;

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
        $strPage = mb_trim($page ?? '');
        
        if (!IntegerRule::check($strPage, 1, PHP_INT_MAX)) {
            $this->value = Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE;
            return ;
        }
        
        $this->value = (int) $strPage;
    }
    
    public static function create(string|null $page): self
    {
        return new self($page);
    }
}
