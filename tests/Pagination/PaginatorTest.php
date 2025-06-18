<?php

use Base\Pagination\Paginator;
use Base\Server\ServerRequestInterface;
use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    private ServerRequestInterface $serverRequest;

    public function test_setOptions_method_install_options_in_case_is_not_full_page(): void
    {
        $page = PageValue::create('3');
        $perPage = PerPageValue::create('10');
        $total = 77;
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        $paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value, $paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $paginator->getPerPage());
        $this->assertEquals($total, $paginator->getTotal());
        $this->assertEquals(($page->value - 1) * $perPage->value, $paginator->getOffset());
        $this->assertEquals(8, $paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_is_full_pages(): void
    {
        $page = PageValue::create('3');
        $perPage = PerPageValue::create('10');
        $total = 50;
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        $paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value, $paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $paginator->getPerPage());
        $this->assertEquals($total, $paginator->getTotal());
        $this->assertEquals(($page->value - 1) * $perPage->value, $paginator->getOffset());
        $this->assertEquals(5, $paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_page_larger_pagesNumber(): void
    {
        $page = PageValue::create('6');
        $perPage = PerPageValue::create('10');
        $total = 50;
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        $paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value - 1, $paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $paginator->getPerPage());
        $this->assertEquals($total, $paginator->getTotal());
        // Так как текущая страница меньше $page->value на 1, нужно взять $page->value - 2
        $this->assertEquals(($page->value - 2) * $perPage->value, $paginator->getOffset());
        $this->assertEquals(5, $paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_one_item_on_one_page(): void
    {
        $page = PageValue::create('1');
        $perPage = PerPageValue::create('10');
        $total = 0;
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        $paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value - 1, $paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $paginator->getPerPage());
        $this->assertEquals($total, $paginator->getTotal());
        $this->assertEquals(0, $paginator->getOffset());
        $this->assertEquals(0, $paginator->getPagesNumber());
    }
    
    public function test_default_case(): void
    {
        $paginator = new Paginator((object) [], $this->serverRequest);
        
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE, $paginator->getCurrentPage());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_TOTAL, $paginator->getTotal());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_PAGES_NUMBER, $paginator->getPagesNumber());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_OFFSET, $paginator->getOffset());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_PER_PAGE, $paginator->getPerPage());
    }
    
    public function test_link_method_return_span(): void
    {
        $page = 1;
        $perPage = 10;
        
        $this->serverRequest->method('getUri')
                ->willReturn('test');
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        
        $link = $paginator->link($page, $perPage, [], true);
        
        $this->assertEquals("<span> $page </span>", trim($link));
    }
    
    public function test_link_method_return_a(): void
    {
        $page = 1;
        $perPage = 10;
        $char = 'text';
        $title = '';
        $description = 'test';
        
        $this->serverRequest->method('getUri')
                ->willReturn('test');
        
        $paginator = new Paginator((object) [], $this->serverRequest);
        
        $link = $paginator->link($page, $perPage, [
            'title' => $title,
            'description' => $description
        ], false, 'text');
        
        $this->assertEquals("<a href=\"/test?page=$page&per_page=$perPage&title=$title&description=$description\"> $char </a>", trim($link));
    }

    protected function setUp(): void
    {
        $this->serverRequest = $this->createStub(ServerRequestInterface::class);
    }
}
