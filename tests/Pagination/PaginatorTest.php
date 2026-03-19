<?php

namespace Tests\Pagination;

use Base\Pagination\Paginator;
use Base\ValueObjects\Pagination\PageValue;
use Base\ValueObjects\Pagination\PerPageValue;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    private Paginator $paginator;

    public function test_setOptions_method_install_options_in_case_is_not_full_page(): void
    {
        $page = PageValue::create('3');
        $perPage = PerPageValue::create('10');
        $total = 77;
        
        $this->paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value, $this->paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $this->paginator->getPerPage());
        $this->assertEquals($total, $this->paginator->getTotal());
        $this->assertEquals(($page->value - 1) * $perPage->value, $this->paginator->getOffset());
        $this->assertEquals(8, $this->paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_is_full_pages(): void
    {
        $page = PageValue::create('3');
        $perPage = PerPageValue::create('10');
        $total = 50;
        
        $this->paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value, $this->paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $this->paginator->getPerPage());
        $this->assertEquals($total, $this->paginator->getTotal());
        $this->assertEquals(($page->value - 1) * $perPage->value, $this->paginator->getOffset());
        $this->assertEquals(5, $this->paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_page_larger_pagesNumber(): void
    {
        $page = PageValue::create('6');
        $perPage = PerPageValue::create('10');
        $total = 50;
        
        $this->paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value - 1, $this->paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $this->paginator->getPerPage());
        $this->assertEquals($total, $this->paginator->getTotal());
        // Так как текущая страница меньше $page->value на 1, нужно взять $page->value - 2
        $this->assertEquals(($page->value - 2) * $perPage->value, $this->paginator->getOffset());
        $this->assertEquals(5, $this->paginator->getPagesNumber());
    }
    
    public function test_setOptions_method_install_options_in_case_one_item_on_one_page(): void
    {
        $page = PageValue::create('1');
        $perPage = PerPageValue::create('10');
        $total = 0;
        
        $this->paginator->setOptions($page, $perPage, $total);
        
        $this->assertEquals($page->value - 1, $this->paginator->getCurrentPage());
        $this->assertEquals($perPage->value, $this->paginator->getPerPage());
        $this->assertEquals($total, $this->paginator->getTotal());
        $this->assertEquals(0, $this->paginator->getOffset());
        $this->assertEquals(0, $this->paginator->getPagesNumber());
    }
    
    public function test_default_case(): void
    {
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_CURRENT_PAGE, $this->paginator->getCurrentPage());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_TOTAL, $this->paginator->getTotal());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_PAGES_NUMBER, $this->paginator->getPagesNumber());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_OFFSET, $this->paginator->getOffset());
        $this->assertEquals(Paginator::PAGINATOR_DEFAULT_PER_PAGE, $this->paginator->getPerPage());
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->paginator = new Paginator();
    }
}
