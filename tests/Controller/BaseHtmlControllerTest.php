<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\BaseRegistration;
use Base\Registration\ModuleRegistration;
use Tests\Sources\Controllers\Html\HtmlController;
use Tests\Sources\Controllers\Html\ErrorController;

class BaseHtmlControllerTest extends TestCase
{
    private Container $container;

    protected function setUp(): void
    {
        $this->container = new Container();

        $config = (object) [
            'app_url' => dirname(__DIR__),
            'template' => __DIR__ . '/../Sources/Views/Html/template.php',
            'views_folder' => __DIR__ . '/../Sources/Views/Html/',
            'error_router' => (object) [
                'controller' => ErrorController::class,
                'action' => 'action',
                'template' => __DIR__ . '/../Sources/Views/Html/Error/errorTemplate.php',
                'views_folder' => __DIR__ . '/../Sources/Views/Html/Error/',
            ],
        ];

        new BaseRegistration($this->container, $config);
        new ModuleRegistration($this->container, $config);
    }

    public function test_render_can_draw(): void
    {
        // Регистрация is_find_route нужна, чтобы проверить правильность нахождения шаблона и папки представлений
        $this->container->register('is_find_route', fn (): bool => true);
        $str = (new HtmlController($this->container))->action();

        $this->assertEquals('begin a=5 b=x end', $str);
    }

    public function test_error_controller(): void
    {
        // Регистрация is_find_route нужна, чтобы проверить правильность нахождения шаблона и папки представлений
        $this->container->register('is_find_route', fn (): bool => false);
        $controller = $this->container->get('error_router')->controller;
        $str = [new $controller($this->container), $this->container->get('error_router')->action]();

        $this->assertEquals('error Страница не найдена error', $str);
    }
}
