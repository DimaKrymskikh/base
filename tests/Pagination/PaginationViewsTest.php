<?php

namespace Tests\Pagination;

use Base\Pagination\PaginationViews;
use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;
use PHPUnit\Framework\TestCase;

class PaginationViewsTest extends TestCase
{
    private PaginationViews $paginationViews;
    
    public function test_success_link(): void
    {
        $page = 1;
        $perPage = 10;
        $char = 'text';
        $title = '';
        $description = 'test';
        
        $link = $this->paginationViews->link($page, $perPage, [
            'title' => $title,
            'description' => $description
        ], $char);
        
        $this->assertEquals("<a href=\"/test?page=$page&per_page=$perPage&title=$title&description=$description\"> $char </a>", trim($link));
    }

    public function test_success_span(): void
    {
        $page = 1;
        $link = $this->paginationViews->span($page);
        
        $this->assertEquals("<span> $page </span>", trim($link));
    }

    public function test_success_links(): void
    {
        $this->paginationViews->setOptions(PageValue::create(1), PerPageValue::create(10), 25);
        
        $this->assertIsString($this->paginationViews->links(__DIR__.'/../../recources/Views/Pagination/pagination.php'));
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->paginationViews = new PaginationViews('test');
    }
}
