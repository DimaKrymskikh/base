<?php

use PHPUnit\Framework\TestCase;
use Base\Router;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router('Tests\Controllers', (object) [
            'controller' => 'ErrorController',
            'action' => 'index'
        ]);
        
        $this->router->get('foo', 'FooController', 'index');
        $this->router->get('foo/{a}', 'FooController', 'get');
        $this->router->post('foo/{a}', 'FooController', 'post');
        $this->router->put('foo/{a}/{b}', 'FooController', 'put');
        $this->router->delete('foo/{a}/str/{b}', 'FooController', 'delete');
        
        $this->router->get('bar', 'BarController');
        $this->router->get('bar/str/{a}', 'BarController', 'get');
        $this->router->post('bar/str/{a}/str2/{b}/{c}/str3', 'BarController', 'post');
        $this->router->put('bar/str/{a}/str2/str3', 'BarController', 'put');
        $this->router->delete('bar/{a}/{b}/str2/str3', 'BarController', 'delete');
    }
    
    public function testFoo() 
    {
        $this->expectOutputString('FooController->index');
        $this->router->run('GET', 'foo');
    }
    
    public function testGet() 
    {
        $this->expectOutputString('FooController->get: 4');
        $this->router->run('get', 'foo/4');
    }
    
    public function testPost() 
    {
        $this->expectOutputString('FooController->post: 4');
        $this->router->run('POST', 'foo/4');
    }
    
    public function testPut() 
    {
        $this->expectOutputString('FooController->put: 4 и 77');
        $this->router->run('PUT', 'foo/4/77');
    }
    
    public function testDelete() 
    {
        $this->expectOutputString('FooController->delete: 4 и abcd');
        $this->router->run('DELETE', 'foo/4/str/abcd');
    }
    
    public function testBar() 
    {
        $this->expectOutputString('BarController->index');
        $this->router->run('GET', 'bar');
    }
    
    public function testBarGet() 
    {
        $this->expectOutputString('BarController->get: 2');
        $this->router->run('GET', 'bar/str/2');
    }
    
    public function testBarPost() 
    {
        $this->expectOutputString('BarController->post: 8, aaaa и bbb');
        $this->router->run('post', 'bar/str/8/str2/aaaa/bbb/str3');
    }
    
    public function testBarPut() 
    {
        $this->expectOutputString('BarController->put: aa');
        $this->router->run('pUt', 'bar/str/aa/str2/str3');
    }
    
    public function testBarDelete() 
    {
        $this->expectOutputString('BarController->delete: aaaaa и bbb');
        $this->router->run('DelEte', 'bar/aaaaa/bbb/str2/str3');
    }
    
    public function testErrorUri() 
    {
        $this->expectOutputString('Страница не найдена');
        $this->router->run('get', 'baz');
    }
    
    public function testErrorMethod() 
    {
        $this->expectOutputString('Страница не найдена');
        $this->router->run('get', 'bar/str/aa/str2/str3');
    }
}
