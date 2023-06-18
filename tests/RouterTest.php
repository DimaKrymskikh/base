<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\BaseRegistration;
use Base\Registration\RequestRegistration;
use Base\Router;
use Tests\Sources\Controllers\FooController;
use Tests\Sources\Controllers\BarController;
use Tests\Sources\Controllers\ErrorController;

class RouterTest extends TestCase
{
    private Router $router;
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();

        $config = (object) [
            'app_url' => 'D:/app',
            'template' => '/X/Y/main.php',
            'views_uri' => '/X/',
            'error_router' => (object) [
                'controller' => ErrorController::class,
            ],
        ];

        new BaseRegistration($this->container, $config);

        $this->router = new Router($this->container);

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

    public function test_slash()
    {
        $request = (object) [
            'method' => 'get',
            'uri' => '/'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->index');
        $this->router->run();
    }

    public function test_foo()
    {
        $request = (object) [
            'method' => 'get',
            'uri' => '/foo'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->index');
        $this->router->run();
    }

    public function test_get()
    {
        $request = (object) [
            'method' => 'get',
            'uri' => '/foo/4'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->get: 4');
        $this->router->run();
    }

    public function test_post()
    {
        $request = (object) [
            'method' => 'POST',
            'uri' => '/foo/4'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->post: 4');
        $this->router->run();
    }

    public function test_put()
    {
        $request = (object) [
            'method' => 'put',
            'uri' => 'foo/4/77'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->put: 4 и 77');
        $this->router->run();
    }

    public function test_delete()
    {
        $request = (object) [
            'method' => 'DELETE',
            'uri' => 'foo/4/str/abcd'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('FooController->delete: 4 и abcd');
        $this->router->run();
    }

    public function test_bar()
    {
        $request = (object) [
            'method' => 'GET',
            'uri' => 'bar'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('BarController->index');
        $this->router->run();
    }

    public function test_bar_get()
    {
        $request = (object) [
            'method' => 'GET',
            'uri' => '/bar/str/2'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('BarController->get: 2');
        $this->router->run();
    }

    public function test_bar_post()
    {
        $request = (object) [
            'method' => 'post',
            'uri' => '/bar/str/8/str2/aaaa/bbb/str3'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('BarController->post: 8, aaaa и bbb');
        $this->router->run();
    }

    public function test_bar_put()
    {
        $request = (object) [
            'method' => 'pUt',
            'uri' => '/bar/str/aa/str2/str3'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('BarController->put: aa');
        $this->router->run();
    }

    public function test_bar_delete()
    {
        $request = (object) [
            'method' => 'DelEte',
            'uri' => 'bar/aaaaa/bbb/str2/str3'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('BarController->delete: aaaaa и bbb');
        $this->router->run();
    }

    public function test_non_existent_uri()
    {
        $request = (object) [
            'method' => 'get',
            'uri' => 'baz'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('Страница не найдена');
        $this->router->run();
    }

    public function test_existent_uri_and_wrong_method()
    {
        $request = (object) [
            'method' => 'get',
            'uri' => 'bar/str/aa/str2/str3'
        ];
        new RequestRegistration($this->container, $request);

        $this->expectOutputString('Страница не найдена');
        $this->router->run();
    }
}
