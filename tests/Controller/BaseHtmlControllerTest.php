<?php

use Base\DataTransferObjects\InputServerDto;
use Base\Foundation\Application;
use Base\Router\ActionOptions;
use Base\Router\Router;
use Base\Support\DB\DB;
use Base\Support\Options;
use PHPUnit\Framework\TestCase;
use Tests\Sources\Controllers\Html\HtmlController;
use Tests\Sources\Controllers\Html\ErrorController;

class BaseHtmlControllerTest extends TestCase
{
    private object $config;

    protected function setUp(): void
    {
        $this->config = (object) [
            // Папка tests
            'app_url' => dirname(__DIR__),
            'template' => '/Sources/Views/Html/template.php',
            'views_folder' => '/Sources/Views/Html/',
            'error_router' => (object) [
                'controller' => ErrorController::class,
                'action' => 'action',
                'template' => '/Sources/Views/Html/Error/errorTemplate.php',
                'views_folder' => '/Sources/Views/Html/Error/',
            ],
        ];
    }

    public function test_render_can_draw(): void
    {
        $db = $this->createStub(DB::class);
        $inputServer = new InputServerDto('get', 'test');
        $finishedСonfig = (new Options($this->config))->config;
        
        $container = (new Application($db, $finishedСonfig, $inputServer))->getContainer();

        $router = new Router($container);
        // метод и uri соответствуют запросу
        $router->get('test', HtmlController::class, 'action');
        $router->setAction();
        
        $appAction = $container->get('action');
        $str = [new $appAction->controller($appAction), $appAction->action](...$appAction->actionArguments);

        $this->assertInstanceOf(ActionOptions::class, $appAction);
        $this->assertEquals('begin value a=5 b=x end', $str);
    }

    public function test_error_controller(): void
    {
        $db = $this->createStub(DB::class);
        $inputServer = new InputServerDto('post', 'test');
        $finishedСonfig = (new Options($this->config))->config;
        
        $container = (new Application($db, $finishedСonfig, $inputServer))->getContainer();

        $router = new Router($container);
        // метод не соответствует запросу
        $router->get('test', HtmlController::class, 'action');
        $router->setAction();
        
        $appAction = $container->get('action');
        $str = [new $appAction->controller($appAction), $appAction->action](...$appAction->actionArguments);

        $this->assertInstanceOf(ActionOptions::class, $appAction);
        $this->assertEquals('error Страница не найдена error', $str);
    }
}
