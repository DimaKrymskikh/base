<?php

use Base\Contracts\Constants\DefaultConfig;
use Base\Support\Options;
use PHPUnit\Framework\TestCase;
use Tests\Sources\config\Config;

class OptionsTest extends TestCase
{
    private object $db;
    
    /**
     * Задан только url приложения
     * 
     * @return void
     */
    public function test_default_config(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'app_debug' => true,
            'template' => DefaultConfig::Template->value,
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'routes_file' => DefaultConfig::RoutesFile->value,
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => DefaultConfig::ErrorAction->value,
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'folder' => DefaultConfig::PaginationFolder->value
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
            ],
            'db' => $this->db,
        ], (new Options(Config::getDefaultConfig($this->db)))->config);
    }
    
    public function test_config_without_modules(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'app_debug' => false,
            'template' => '/Views/Layout/template.php',
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'routes_file' => '/../routes/routes.php',
            'error_router' => (object) [
                'controller' => 'App\Controllers\DefaultController',
                'action' => DefaultConfig::ErrorAction->value,
                'template' => '/Views/Layout/errorTemplate.php',
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'folder' => DefaultConfig::PaginationFolder->value
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
            ],
            'db' => $this->db,
        ], (new Options( Config::getConfigWithoutModules($this->db) ))->config);
    }
    
    public function test_config_with_modules(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'app_debug' => false,
            'template' => DefaultConfig::Template->value,
            'views_folder' => '/Http/Views/',
            'routes_file' => DefaultConfig::RoutesFile->value,
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => 'action',
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => '/Http/Views/error/'
            ],
            'pagination' => (object) [
                'folder' => DefaultConfig::PaginationFolder->value
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
            ],
            'db' => $this->db,
        ], (new Options( Config::getConfigWithModules($this->db) ))->config);
    }
    
    public function test_config_with_logs(): void
    {
        $this->assertEqualsCanonicalizing((object) [
            'app_url' => 'test',
            'app_debug' => false,
            'template' => DefaultConfig::Template->value,
            'views_folder' => DefaultConfig::ViewsFolder->value,
            'routes_file' => DefaultConfig::RoutesFile->value,
            'error_router' => (object) [
                'controller' => DefaultConfig::ErrorController->value,
                'action' => DefaultConfig::ErrorAction->value,
                'template' => DefaultConfig::ErrorTemplate->value,
                'views_folder' => DefaultConfig::ViewsFolder->value,
            ],
            'pagination' => (object) [
                'folder' => DefaultConfig::PaginationFolder->value
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
            ],
            'db' => $this->db,
        ], (new Options(Config::getConfigWithLogs($this->db)))->config);
    }

    #[\Override]
    protected function setUp(): void
    {
        $this->db = require __DIR__.'/../../config/db.php';
    }
}
