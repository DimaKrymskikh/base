<?php

namespace Base\Contracts\Constants;

/*
 * Дефолтные настройки конфигурации.
 * Маршруты указаны относительно app_url
 */
enum DefaultConfig: string
{
    case Template = '/Views/template.php';
    case ViewsFolder = '/Views/';
    case ErrorController = 'App\Controllers\ErrorController';
    case ErrorAction = 'index';
    case ErrorTemplate = '/Views/errorTemplate.php';
    case LogsAssetsFolder = '../storage/logs/assets';
    case LogsErrorsFolder = '../storage/logs/errors';
    case LogsAssetsFile = 'assets';
    case LogsErrorsFile = 'errors';
    case PaginationView = '/Views/Pagination/pagination.php';
    case RoutesFile = '/../routes/web.php';
}
