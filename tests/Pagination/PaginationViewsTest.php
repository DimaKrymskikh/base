<?php

namespace Tests\Pagination;

use Base\Container\Container;
use Base\Pagination\PaginationViews;
use Base\Pagination\Paginator;
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
        $span = $this->paginationViews->span($page);
        
        $this->assertEquals("<span> $page </span>", trim($span));
    }

    public function test_success_links(): void
    {
        $container = Container::getInstance();
        $container->set('config', (object) [
            'app_url' => __DIR__,
            'pagination' => (object) [
                'folder' => '/../../recources/Views/Pagination/',
            ],
        ]);
        
        $this->assertIsString($this->paginationViews->links('pagination.php'));
        
        $container->flush();
    }

    #[\Override]
    protected function setUp(): void
    {
        $paginator = new Paginator();
        $paginator->setOptions(PageValue::create(1), PerPageValue::create(10), 25);
        
        $this->paginationViews = new PaginationViews('/test/', $paginator);
    }
}
