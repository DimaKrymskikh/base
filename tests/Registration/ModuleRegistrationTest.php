<?php

use PHPUnit\Framework\TestCase;
use Base\Container\Container;
use Base\Registration\BaseRegistration;
use Base\Registration\ModuleRegistration;
use Base\Registration\RequestRegistration;

class ModuleRegistrationTest extends TestCase
{
    private Container $container;
    private object $config;
    private array $modules;

    protected function setUp(): void
    {
        $this->container = new Container();
        $this->config = (object) [
            'app_url' => 'D:/app',
            'template' => 'D:/X/Y/main.php',
            'views_folder' => 'D:/X/'
        ];

        $this->modules = [
            'ma' => (object) [
                'pattern' => 'm/a',
                'views_folder' => 'D:/app/a/Views/',
            ],
            'mb' => (object) [
                'pattern' => 'm/b',
                'views_folder' => 'D:/app/b/Views/',
                'template' => 'D:/app/b/main.php'
            ],
            'mc' => (object) [
                'pattern' => 'm/c',
            ],
        ];

        new BaseRegistration($this->container, $this->config);
    }

    public function test_registration_can_without_modules(): void
    {
        new ModuleRegistration($this->container, $this->config);

        $this->assertEquals('D:/X/Y/main.php', $this->container->get('module')->template);
        $this->assertEquals('D:/X/', $this->container->get('module')->views_folder);
    }

    public function test_registration_can_with_module_a(): void
    {
        $this->config->modules = $this->modules;
        $request = (object) [
            'method' => 'get',
            'uri' => 'm/a/1/x'
        ];

        new RequestRegistration($this->container, $request);
        new ModuleRegistration($this->container, $this->config);

        $this->assertEquals('D:/X/Y/main.php', $this->container->get('module')->template);
        $this->assertEquals('D:/app/a/Views/', $this->container->get('module')->views_folder);
    }

    public function test_registration_can_with_module_b(): void
    {
        $this->config->modules = $this->modules;
        $request = (object) [
            'method' => 'post',
            'uri' => 'm/b/1/x'
        ];

        new RequestRegistration($this->container, $request);
        new ModuleRegistration($this->container, $this->config);

        $this->assertEquals('D:/app/b/main.php', $this->container->get('module')->template);
        $this->assertEquals('D:/app/b/Views/', $this->container->get('module')->views_folder);
    }

    public function test_registration_can_with_module_c(): void
    {
        $this->config->modules = $this->modules;
        $request = (object) [
            'method' => 'put',
            'uri' => 'm/c/1/x'
        ];

        new RequestRegistration($this->container, $request);
        new ModuleRegistration($this->container, $this->config);

        $this->assertEquals('D:/X/Y/main.php', $this->container->get('module')->template);
        $this->assertEquals('D:/X/', $this->container->get('module')->views_folder);
    }
}
