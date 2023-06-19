<?php

namespace Base\Contracts\Constants;

/*
 * Дефолтные настройка конфигурации
 */
enum DefaultConfig: string
{
    case Template = '/Views/template.php';
    case ViewsFolder = '/Views/';
    case ErrorController = 'App\Controllers\ErrorController';
    case ErrorAction = 'index';
    case ErrorTemplate = '/Views/errorTemplate.php';
}
