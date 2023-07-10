<?php

namespace Base\Registration;

use Base\Contracts\Constants\DefaultConfig;
use Base\Container\Container;
use Base\Contracts\Registration\Registration;

class BaseRegistration extends Registration
{
    protected function register(Container $container, object $config): void
    {
        // В конфигурации приложения обязательно должен быть задан url приложения
        if(!isset($config->app_url)) {
            throw new \Exception('В конфигурации не задан url приложения');
        }
        // Регистрируем url приложения
        $container->register('app_url', fn (): string => $config->app_url);

        // Остальные главные поля конфигурации регистрируем по определению в конфигурации,
        // а если в конфигурации поле отсутствует, то задаём значение по-умолчанию
        $container->register('template', fn (): string => isset($config->template) ? $config->template : $container->get('app_url') . DefaultConfig::Template->value);
        $container->register('views_folder', fn (): string => isset($config->views_folder) ? $config->views_folder : $container->get('app_url') . DefaultConfig::ViewsFolder->value);
        $container->register(
            'error_router',
            fn (): object => isset($config->error_router)
            ? (object) [
                    'controller' => isset($config->error_router->controller) ? $config->error_router->controller : DefaultConfig::ErrorController->value,
                    'action' => isset($config->error_router->action) ? $config->error_router->action : DefaultConfig::ErrorAction->value,
                    'template' => isset($config->error_router->template) ? $config->error_router->template : $container->get('app_url') . DefaultConfig::ErrorTemplate->value,
                    'views_folder' => isset($config->error_router->views_folder) ? $config->error_router->views_folder : $container->get('app_url') . DefaultConfig::ViewsFolder->value,
                ]
            : (object) [
                    'controller' => DefaultConfig::ErrorController->value,
                    'action' => DefaultConfig::ErrorAction->value,
                    'template' => $container->get('app_url') . DefaultConfig::ErrorTemplate->value,
                    'views_folder' => $container->get('app_url') . DefaultConfig::ViewsFolder->value,
                ]
        );
        $container->register('spa_index', fn (): string => isset($config->spa_index) ? $config->spa_index : $container->get('app_url') . DefaultConfig::SpaIndex->value);

        // Если в приложении имеются переменные окружения, регистрируем переменные окружения
        if(isset($config->env)) {
            $container->register('env', fn (): object => $config->env);
        }

        // Если в приложении имеются параметры базы данных, регистрируем параметры базы данных
        if(isset($config->db)) {
            $container->register('db', fn (): object => $config->db);
        }
    }
}
