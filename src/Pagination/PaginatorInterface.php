<?php

namespace Base\Pagination;

use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;

interface PaginatorInterface
{
    public function setOptions(PageValue $page, PerPageValue $perPage, int $total): void;
    
    public function getTotal(): int;
    
    public function getPagesNumber(): int;
    
    public function getPerPage(): int;
     
    public function getCurrentPage(): int;
    
    public function getOffset(): int;
    
    public function link(int $page, int $perPage, array $fields = [], bool $isCurrentPage = false, string|null $char = null): string;
    
    public function links(array $fields = []): string;
}
