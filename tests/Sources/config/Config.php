<?php

namespace Tests\Sources\config;

class Config
{
    public static function getEmptyConfig(): object
    {
        return (object) [];
    }
    
    public static function getDefaultConfig(): object
    {
        return (object)[
            'app_url' => 'test'
        ];
    }
    
    public static function getConfigWithoutModules(): object
    {
        return $config = (object)[
            'app_url' => 'test',
            'template' => '/Views/Layout/template.php',
            'error_router' => (object) [
                'controller' => 'App\Controllers\DefaultController',
                'template' => '/Views/Layout/errorTemplate.php',
            ],
        ];
    }
    
    public static function getConfigWithModules(): object
    {
        return $config = (object)[
            'app_url' => 'test',
            'views_folder' => '/Http/Views/',
            'error_router' => (object) [
                'views_folder' => '/Http/Views/error/',
                'action' => 'action',
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
        ];
    }
    
    public static function getConfigWithLogs(): object
    {
        return $config = (object)[
            'app_url' => 'test',
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
        ];
    }
}
