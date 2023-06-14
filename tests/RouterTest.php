<?php

use PHPUnit\Framework\TestCase;
use Base\Router;
use Tests\Controllers\FooController;
use Tests\Controllers\BarController;
use Tests\Controllers\ErrorController;

class RouterTest extends TestCase
{
    private Router $router;

    protected function setUp(): void
    {
        $this->router = new Router((object) [
            'controller' => ErrorController::class,
            'action' => 'index'
        ]);

        $this->router->get('/', FooController::class, 'index');
        $this->router->get('foo', FooController::class, 'index');
        $this->router->get('foo/{a}', FooController::class, 'get');
        $this->router->post('foo/{a}', FooController::class, 'post');
        $this->router->put('foo/{a}/{b}', FooController::class, 'put');
        $this->router->delete('foo/{a}/str/{b}', FooController::class, 'delete');

        $this->router->get('bar', BarController::class);
        $this->router->get('bar/str/{a}', BarController::class, 'get');
        $this->router->post('bar/str/{a}/str2/{b}/{c}/str3', BarController::class, 'post');
        $this->router->put('bar/str/{a}/str2/str3', BarController::class, 'put');
        $this->router->delete('bar/{a}/{b}/str2/str3', BarController::class, 'delete');
    }

    public function testSlash()
    {
        $this->expectOutputString('FooController->index');
        $this->router->run('GET', '/');
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
