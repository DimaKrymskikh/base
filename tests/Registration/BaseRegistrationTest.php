<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\BaseRegistration;

class BaseRegistrationTest extends TestCase
{
    public function test_registration_can_not_if_app_url_not_set(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('В конфигурации не задан url приложения');
        
        $container = new Container();
        $config = (object) [];
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
        $this->assertEquals('D:/app/Views/', $container->get('views_folder'));
        $this->assertEmpty($container->get('db'));
        $this->assertEmpty($container->get('env'));
    }

    public function test_registration_can_if_app_url_set(): void
    {
        $container = new Container();
        $config = (object) [
            'app_url' => 'D:/app',
            'template' => '/X/Y/main.php',
            'views_folder' => '/X/',
            'env' => (object)[
                'token' => 'aaa',
                'domain' => 'bbb',
            ]
        ];

        new BaseRegistration($container, $config);

        $this->assertEquals('/X/Y/main.php', $container->get('template'));
        $this->assertEquals('/X/', $container->get('views_folder'));
        $this->assertEmpty($container->get('db'));
        $this->assertEquals((object)[
                'token' => 'aaa',
                'domain' => 'bbb',
            ], $container->get('env'));
    }
}
