<?php

namespace Base\Support;

use Base\Contracts\Constants\DefaultConfig;

readonly class Options
{
    public object $config;
    
    public function __construct(object $config)
    {
        $this->config = $this->setBaseOptions($config);
    }
    
    private function setBaseOptions(object $config): object
    {
        $appConfig = (object) [];
        
        // В конфигурации приложения обязательно должен быть задан url приложения
        if(!isset($config->app_url)) {
            throw new \Exception('В конфигурации не задан url приложения');
        }
        $appConfig->app_url = $config->app_url;

        // Остальные главные поля конфигурации регистрируем по определению в конфигурации,
        // а если в конфигурации поле отсутствует, то задаём значение по-умолчанию
        $appConfig->template = isset($config->template) ? $config->template : DefaultConfig::Template->value;
        $appConfig->views_folder = isset($config->views_folder) ? $config->views_folder : DefaultConfig::ViewsFolder->value;
        $appConfig->error_router = isset($config->error_router)
            ? (object) [
                    'controller' => isset($config->error_router->controller) ? $config->error_router->controller : DefaultConfig::ErrorController->value,
                    'action' => isset($config->error_router->action) ? $config->error_router->action : DefaultConfig::ErrorAction->value,
                    'template' => isset($config->error_router->template) ? $config->error_router->template : DefaultConfig::ErrorTemplate->value,
                    'views_folder' => isset($config->error_router->views_folder) ? $config->error_router->views_folder : DefaultConfig::ViewsFolder->value,
                ]
            : (object) [
                    'controller' => DefaultConfig::ErrorController->value,
                    'action' => DefaultConfig::ErrorAction->value,
                    'template' => DefaultConfig::ErrorTemplate->value,
                    'views_folder' => DefaultConfig::ViewsFolder->value,
                ];
        
        // Сохраняем поле modules
        $appConfig->modules = isset($config->modules) ? $config->modules : [];
        
        $appConfig->logs = (object) [
            'assets' => (object) [
                'folder' => $config->logs?->assets?->folder ?? DefaultConfig::LogsAssetsFolder->value,
                'file' => $config->logs?->assets?->file ?? DefaultConfig::LogsAssetsFile->value,
            ],
            'errors' => (object) [
                'folder' => $config->logs?->errors?->folder ?? DefaultConfig::LogsErrorsFolder->value,
                'file' => $config->logs?->errors?->file ?? DefaultConfig::LogsErrorsFile->value,
            ]
        ];
        
        $appConfig->pagination = $this->setPaginationObtions($config);
        
        return $appConfig;
    }
    
    private function setPaginationObtions(object $config): object
    {
        return (object) [
            'view' => $config->pagination?->view ?? DefaultConfig::PaginationView
        ];
    }
}
