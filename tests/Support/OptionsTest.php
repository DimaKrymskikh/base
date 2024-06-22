<?php

use Base\Support\Options;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class OptionsTest extends TestCase
{
    public function test_empty_config(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('В конфигурации не задан url приложения');
        new Options(Config::getEmptyConfig());
    }
    
    /**
     * Задан только url приложения
     * 
     * @return void
     */
    public function test_default_config(): void
    {
        $this->assertEquals((object) [
            'app_url' => 'test',
            'template' => '/Views/template.php',
            'views_folder' => '/Views/',
            'error_router' => (object) [
                'controller' => 'App\Controllers\ErrorController',
                'action' => 'index',
                'template' => '/Views/errorTemplate.php',
                'views_folder' => '/Views/'
            ],
            'modules' => []
        ], (new Options(Config::getDefaultConfig()))->config);
    }
    
    public function test_config_without_modules(): void
    {
        $this->assertEquals((object) [
            'app_url' => 'test',
            'template' => '/Views/Layout/template.php',
            'views_folder' => '/Views/',
            'error_router' => (object) [
                'controller' => 'App\Controllers\DefaultController',
                'action' => 'index',
                'template' => '/Views/Layout/errorTemplate.php',
                'views_folder' => '/Views/'
            ],
            'modules' => []
        ], (new Options( Config::getConfigWithoutModules() ))->config);
    }
    
    public function test_config_with_modules(): void
    {
        $this->assertEquals((object) [
            'app_url' => 'test',
            'template' => '/Views/template.php',
            'views_folder' => '/Http/Views/',
            'error_router' => (object) [
                'controller' => 'App\Controllers\ErrorController',
                'action' => 'action',
                'template' => '/Views/errorTemplate.php',
                'views_folder' => '/Http/Views/error/'
            ],
            'modules' => [
                'module_one' => (object) [
                    'pattern' => 'one',
                    'views_folder' => '/app/ModuleOne/Views/',
                ],
                'module_two' => (object) [
                    'pattern' => 'two',
                    'views_folder' => '/app/ModuleTwo/Views/',
                    'template' => '/app/ModuleTwo/Views/template.php',
                ],
            ]
        ], (new Options( Config::getConfigWithModules() ))->config);
    }
}
