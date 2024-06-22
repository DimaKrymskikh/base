<?php

use Base\DataTransferObjects\InputServerDto;
use Base\Foundation\Application;
use Base\Support\DB\DB;
use Base\Support\Options;
use Base\Support\Router;
use PHPUnit\Framework\TestCase;
use Tests\Sources\Controllers\FooController;
use Tests\Sources\Controllers\BarController;
use Tests\Sources\config\Config;

class RouterTest extends TestCase
{
    private Router $router;
    private Application $app;

    public function test_slash()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', '/');

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'index',
            'arr_arg' => [],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_foo()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', 'foo');

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'index',
            'arr_arg' => [],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    // Страница не найдена
    public function test_foobag()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', 'foobag');

        $this->assertEquals((object) [
            // Дефолтный контроллер
            'controller' => 'App\Controllers\ErrorController',
            // Определено в Config::getConfigWithModules()
            'action' => 'action',
            'arr_arg' => ['Страница не найдена'],
            'template' => 'test/Views/errorTemplate.php',
            'views_folder' => 'test/Http/Views/error/',
        ], $this->app->make('action'));
    }

    public function test_get()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', '/foo/4');
        $this->router->setAction();

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'get',
            'arr_arg' => ['4'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_post()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'POST', '/foo/4');

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'post',
            'arr_arg' => ['4'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_put()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'put', 'foo/4/77');

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'put',
            'arr_arg' => ['4', '77'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_delete()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'DELETE', 'foo/4/str/abcd');

        $this->assertEquals((object) [
            'controller' => FooController::class,
            'action' => 'delete',
            'arr_arg' => ['4', 'abcd'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_bar()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'GET', 'bar');

        $this->assertEquals((object) [
            'controller' => BarController::class,
            'action' => 'index',
            'arr_arg' => [],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_bar_get()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'GET', '/bar/str/2');

        $this->assertEquals((object) [
            'controller' => BarController::class,
            'action' => 'get',
            'arr_arg' => ['2'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_bar_post()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'post', '/bar/str/8/str2/aaaa/bbb/str3');

        $this->assertEquals((object) [
            'controller' => BarController::class,
            'action' => 'post',
            'arr_arg' => ['8', 'aaaa', 'bbb'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_bar_put()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'pUt', '/bar/str/aa/str2/str3');

        $this->assertEquals((object) [
            'controller' => BarController::class,
            'action' => 'put',
            'arr_arg' => ['aa'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_bar_delete()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'DelEte', 'bar/aaaaa/bbb/str2/str3');

        $this->assertEquals((object) [
            'controller' => BarController::class,
            'action' => 'delete',
            'arr_arg' => ['aaaaa', 'bbb'],
            'template' => 'test/Views/template.php',
            'views_folder' => 'test/Http/Views/',
        ], $this->app->make('action'));
    }

    public function test_non_existent_uri()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', 'baz');
        $this->router->setAction();

        $this->assertEquals((object) [
            'controller' => 'App\Controllers\ErrorController',
            'action' => 'action',
            'arr_arg' => ['Страница не найдена'],
            'template' => 'test/Views/errorTemplate.php',
            'views_folder' => 'test/Http/Views/error/',
        ], $this->app->make('action'));
    }

    public function test_existent_uri_and_wrong_method()
    {
        $config = (new Options( Config::getConfigWithModules() ))->config;
        $this->runRouters($config, 'get', 'bar/str/aa/str2/str3');

        $this->assertEquals((object) [
            'controller' => 'App\Controllers\ErrorController',
            'action' => 'action',
            'arr_arg' => ['Страница не найдена'],
            'template' => 'test/Views/errorTemplate.php',
            'views_folder' => 'test/Http/Views/error/',
        ], $this->app->make('action'));
    }
    
    private function runRouters(object $config, string $method, string $uri): void
    {
        $db = $this->createStub(DB::class);
        $inputServer = new InputServerDto($method, $uri);
        $this->app = new Application($db, $config, $inputServer);

        $this->router = new Router($this->app);

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
