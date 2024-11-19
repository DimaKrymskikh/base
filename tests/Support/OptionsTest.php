<?php

use Base\Contracts\Constants\DefaultConfig;
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
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'template' => DefaultConfig::Template->value,
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => DefaultConfig::ErrorAction->value,
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'view' => DefaultConfig::PaginationView
            ],
            'modules' => [],
            'logs' => (object) [
                'assets' => (object) [
                    'folder' => DefaultConfig::LogsAssetsFolder->value,
                    'file' => DefaultConfig::LogsAssetsFile->value,
                ],
                'errors' => (object) [
                    'folder' => DefaultConfig::LogsErrorsFolder->value,
                    'file' => DefaultConfig::LogsErrorsFile->value,
                ]
            ]
        ], (new Options(Config::getDefaultConfig()))->config);
    }
    
    public function test_config_without_modules(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'template' => '/Views/Layout/template.php',
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'error_router' => (object) [
                'controller' => 'App\Controllers\DefaultController',
                'action' => DefaultConfig::ErrorAction->value,
                'template' => '/Views/Layout/errorTemplate.php',
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'view' => DefaultConfig::PaginationView
            ],
            'modules' => [],
            'logs' => (object) [
                'assets' => (object) [
                    'folder' => DefaultConfig::LogsAssetsFolder->value,
                    'file' => DefaultConfig::LogsAssetsFile->value,
                ],
                'errors' => (object) [
                    'folder' => DefaultConfig::LogsErrorsFolder->value,
                    'file' => DefaultConfig::LogsErrorsFile->value,
                ]
            ]
        ], (new Options( Config::getConfigWithoutModules() ))->config);
    }
    
    public function test_config_with_modules(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'template' => DefaultConfig::Template->value,
            'views_folder' => '/Http/Views/',
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => 'action',
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => '/Http/Views/error/'
            ],
            'pagination' => (object) [
                'view' => DefaultConfig::PaginationView
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
            ],
            'logs' => (object) [
                'assets' => (object) [
                    'folder' => DefaultConfig::LogsAssetsFolder->value,
                    'file' => DefaultConfig::LogsAssetsFile->value,
                ],
                'errors' => (object) [
                    'folder' => DefaultConfig::LogsErrorsFolder->value,
                    'file' => DefaultConfig::LogsErrorsFile->value,
                ]
            ]
        ], (new Options( Config::getConfigWithModules() ))->config);
    }
    
    public function test_config_with_logs(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'template' => DefaultConfig::Template->value,
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => DefaultConfig::ErrorAction->value,
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'view' => DefaultConfig::PaginationView
            ],
            'modules' => [],
            'logs' => (object) [
                'assets' => (object) [
                    'folder' => '/storage/logs/assets',
                    'file' => 'ass',
                ],
                'errors' => (object) [
                    'folder' => '/storage/logs/errors',
                    'file' => 'err',
                ]
            ]
        ], (new Options(Config::getConfigWithLogs()))->config);
    }
}
