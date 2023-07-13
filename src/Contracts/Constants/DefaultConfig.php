<?php

namespace Base\Contracts\Constants;

/*
 * Дефолтные настройка конфигурации.
 * Маршруты указаны относительно app_url
 */
enum DefaultConfig: string
{
    case Template = '/Views/template.php';
    case ViewsFolder = '/Views/';
    case ErrorController = 'App\Controllers\ErrorController';
    case ErrorAction = 'index';
    case ErrorTemplate = '/Views/errorTemplate.php';
}
