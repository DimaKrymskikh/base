<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\BaseRegistration;

class BaseRegistrationTest extends TestCase
{
    public function test_registration_can_not_if_app_url_not_set(): void
    {
        $container = new Container();
        $config = (object) [];

        $this->expectErrorMessage('В конфигурации не задан url приложения');
        new BaseRegistration($container, $config);
    }

    public function test_registration_can_if_app_url_set_with_default(): void
    {
        $container = new Container();
        $config = (object) [
            'app_url' => 'D:/app'
        ];

        new BaseRegistration($container, $config);

        $this->assertEquals('D:/app/Views/template.php', $container->get('template'));
        $this->assertEquals('D:/app/Views/', $container->get('views_uri'));
    }

    public function test_registration_can_if_app_url_set(): void
    {
        $container = new Container();
        $config = (object) [
            'app_url' => 'D:/app',
            'template' => '/X/Y/main.php',
            'views_uri' => '/X/'
        ];

        new BaseRegistration($container, $config);

        $this->assertEquals('/X/Y/main.php', $container->get('template'));
        $this->assertEquals('/X/', $container->get('views_uri'));
    }
}
