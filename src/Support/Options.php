<?php

namespace Base\Support;

use Base\Contracts\Constants\DefaultConfig;

/**
 * Задаёт конфигурацию приложения.
 */
final readonly class Options
{
    /**
     * Конфигурация приложения.
     * 
     * @var object
     */
    public object $config;
    
    /**
     * Обрабатывает настройки конфигурации.
     * 
     * @param object $config
     */
    public function __construct(object $config)
    {
        $this->config = $this->setOptions($config);
    }
    
    private function setOptions(object $config): object
    {
        $appConfig = (object) [];
        
        [
            $appConfig->app_url,
            $appConfig->app_debug,
            $appConfig->template,
            $appConfig->views_folder,
            $appConfig->error_router,
            $appConfig->routes_file,
        ] = $this->setMainOptions($config);
        
        $appConfig->db = $config->db;
        
        // Сохраняем поле modules
        $appConfig->modules = $config->modules ?? [];
        
        $appConfig->logs = $this->setLogsOptions($config);
        
        $appConfig->pagination = $this->setPaginationOptions($config);
        
        return $appConfig;
    }
    
    /**
     * Задаёт главные поля конфигурации по определению в конфигурации,
     * а если в конфигурации поле отсутствует, то задаём значение по-умолчанию
     * 
     * @param object $config
     * @return array
     */
    private function setMainOptions(object $config): array
    {
        // Если параметр конфигурации 'app_debug' не задан, то считаем, что app_debug = false, 
        // чтобы ошибки писались в логи.
        $appDebug = $config->app_debug ?? false;
        $template = $config->template ?? DefaultConfig::Template->value;
        $viewsFolder = $config->views_folder ?? DefaultConfig::ViewsFolder->value;
        $routesFile = $config->routes_file ?? DefaultConfig::RoutesFile->value;
        
        $errorRouter = isset($config->error_router)
            ? (object) [
                    'controller' => $config->error_router->controller ?? DefaultConfig::ErrorController->value,
                    'action' => $config->error_router->action ?? DefaultConfig::ErrorAction->value,
                    'template' => $config->error_router->template ?? DefaultConfig::ErrorTemplate->value,
                    'views_folder' => $config->error_router->views_folder ?? DefaultConfig::ViewsFolder->value,
                ]
            : (object) [
                    'controller' => DefaultConfig::ErrorController->value,
                    'action' => DefaultConfig::ErrorAction->value,
                    'template' => DefaultConfig::ErrorTemplate->value,
                    'views_folder' => DefaultConfig::ViewsFolder->value,
                ];
        
        return [$config->app_url, $appDebug, $template, $viewsFolder, $errorRouter, $routesFile];
    }
    
    private function setLogsOptions(object $config): object
    {
        return (object) [
            'assets' => (object) [
                'folder' => $config->logs?->assets?->folder ?? DefaultConfig::LogsAssetsFolder->value,
                'file' => $config->logs?->assets?->file ?? DefaultConfig::LogsAssetsFile->value,
            ],
            'errors' => (object) [
                'folder' => $config->logs?->errors?->folder ?? DefaultConfig::LogsErrorsFolder->value,
                'file' => $config->logs?->errors?->file ?? DefaultConfig::LogsErrorsFile->value,
            ]
        ];
    }
    
    private function setPaginationOptions(object $config): object
    {
        return (object) [
            'folder' => $config->pagination?->folder ?? DefaultConfig::PaginationFolder->value
        ];
    }
}
