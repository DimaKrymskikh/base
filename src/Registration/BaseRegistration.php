<?php

namespace Base\Registration;

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
        $container->register('template', fn (): string => isset($config->template) ? $config->template : $container->get('app_url') . '/Views/template.php');
        $container->register('views_uri', fn (): string => isset($config->views_uri) ? $config->views_uri : $container->get('app_url') . '/Views/');
        $container->register(
            'error_router',
            fn (): object => isset($config->error_router)
            ? (object) [
                    'controller' => isset($config->error_router->controller) ? $config->error_router->controller : 'App\Controllers\ErrorController',
                    'action' => isset($config->error_router->action) ? $config->error_router->action : 'index',
                    'template' => isset($config->error_router->template) ? $config->error_router->template : $container->get('app_url') . '/Views/errorTemplate.php'
                ]
            : (object) [
                    'controller' => 'App\Controllers\ErrorController',
                    'action' => 'index',
                    'template' => $container->get('app_url') . '/Views/errorTemplate.php'
                ]
        );
    }
}
