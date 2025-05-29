<?php

use Base\Container\Container;
use Base\Router\Router;
use Base\Server\ServerRequestInterface;
use Base\Support\Options;
use Base\Support\RequestModule;
use PHPUnit\Framework\TestCase;
use Tests\Sources\Controllers\FooController;
use Tests\Sources\Controllers\BarController;
use Tests\Sources\config\Config;

class RouterTest extends TestCase
{
    private Router $router;
    private Container $container;

    public function test_slash(): void
    {
        $this->runRouters('get', '/');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('index', $action->action);
        $this->assertEquals([], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_foo(): void
    {
        $this->runRouters('get', 'foo');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('index', $action->action);
        $this->assertEquals([], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    // Страница не найдена
    public function test_foobag(): void
    {
        $this->runRouters('get', 'foobag');

        $action = $this->container->get('action');
        // Дефолтный контроллер
        $this->assertEquals('App\Controllers\ErrorController', $action->controller);
        // Определено в Config::getConfigWithModules()
        $this->assertEquals('action', $action->action);
        $this->assertEquals(['Страница не найдена'], $action->actionArguments);
        $this->assertEquals('test/Views/errorTemplate.php', $action->template);
        $this->assertEquals('test/Http/Views/error/', $action->viewsFolder);
    }

    public function test_get(): void
    {
        $this->runRouters('get', '/foo/4');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('get', $action->action);
        $this->assertEquals(['4'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_post(): void
    {
        $this->runRouters('POST', '/foo/4');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('post', $action->action);
        $this->assertEquals(['4'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_put(): void
    {
        $this->runRouters('put', 'foo/4/77');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('put', $action->action);
        $this->assertEquals(['4', '77'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_delete(): void
    {
        $this->runRouters('DELETE', 'foo/4/str/abcd');

        $action = $this->container->get('action');
        $this->assertEquals(FooController::class, $action->controller);
        $this->assertEquals('delete', $action->action);
        $this->assertEquals(['4', 'abcd'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_bar(): void
    {
        $this->runRouters('GET', 'bar');

        $action = $this->container->get('action');
        $this->assertEquals(BarController::class, $action->controller);
        $this->assertEquals('index', $action->action);
        $this->assertEquals([], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_bar_get(): void
    {
        $this->runRouters('GET', '/bar/str/2');

        $action = $this->container->get('action');
        $this->assertEquals(BarController::class, $action->controller);
        $this->assertEquals('get', $action->action);
        $this->assertEquals(['2'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_bar_post(): void
    {
        $this->runRouters('post', '/bar/str/8/str2/aaaa/bbb/str3');

        $action = $this->container->get('action');
        $this->assertEquals(BarController::class, $action->controller);
        $this->assertEquals('post', $action->action);
        $this->assertEquals(['8', 'aaaa', 'bbb'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_bar_put(): void
    {
        $this->runRouters('pUt', '/bar/str/aa/str2/str3');

        $action = $this->container->get('action');
        $this->assertEquals(BarController::class, $action->controller);
        $this->assertEquals('put', $action->action);
        $this->assertEquals(['aa'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_bar_delete(): void
    {
        $this->runRouters('DelEte', 'bar/aaaaa/bbb/str2/str3');

        $action = $this->container->get('action');
        $this->assertEquals(BarController::class, $action->controller);
        $this->assertEquals('delete', $action->action);
        $this->assertEquals(['aaaaa', 'bbb'], $action->actionArguments);
        $this->assertEquals('test/Views/template.php', $action->template);
        $this->assertEquals('test/Http/Views/', $action->viewsFolder);
    }

    public function test_non_existent_uri(): void
    {
        $this->runRouters('get', 'baz');

        $action = $this->container->get('action');
        $this->assertEquals('App\Controllers\ErrorController', $action->controller);
        $this->assertEquals('action', $action->action);
        $this->assertEquals(['Страница не найдена'], $action->actionArguments);
        $this->assertEquals('test/Views/errorTemplate.php', $action->template);
        $this->assertEquals('test/Http/Views/error/', $action->viewsFolder);
    }

    public function test_existent_uri_and_wrong_method(): void
    {
        $this->runRouters('get', 'bar/str/aa/str2/str3');

        $action = $this->container->get('action');
        $this->assertEquals('App\Controllers\ErrorController', $action->controller);
        $this->assertEquals('action', $action->action);
        $this->assertEquals(['Страница не найдена'], $action->actionArguments);
        $this->assertEquals('test/Views/errorTemplate.php', $action->template);
        $this->assertEquals('test/Http/Views/error/', $action->viewsFolder);
    }
    
    private function runRouters(string $method, string $uri): void
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $serverRequest = $this->createStub(ServerRequestInterface::class);
        
        $serverRequest->method('getMethod')->willReturn($method);
        $serverRequest->method('getUri')->willReturn($uri);
        
        $this->container = new Container();
        $this->container->set('config', $config);
        $this->container->set('requestModule', (new RequestModule($config, $serverRequest))->request);

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
        
        $this->router->setAction();
    }
}
