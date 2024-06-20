<?php

use Base\Support\Request;
use Base\Support\Options;
use PHPUnit\Framework\TestCase;
use Tests\Sources\Controllers\Html\HtmlController;
use Tests\Sources\Controllers\Html\ErrorController;

class BaseHtmlControllerTest extends TestCase
{
    private array $storage = [];

    protected function setUp(): void
    {
        $config = (object) [
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

        $finishedСonfig = (new Options($config))->config;
        $this->storage = [
            'db' => 'Ненужно в этом тесте',
            'config' => $finishedСonfig,
            // Параметры 'get' и 'test' на тест не влияют
            'request' => (new Request($finishedСonfig, 'get', 'test'))->request
        ];
    }

    public function test_render_can_draw(): void
    {
        $str = (new HtmlController($this->storage))->action();

        $this->assertEquals('begin value a=5 b=x end', $str);
    }
}
