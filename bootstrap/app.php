<?php

use Base\Foundation\Application;

/**
 * В этом файле должны быть определены параметры соединения с базой данных
 * Нужно скопировать файл options.example.php в options.php и заменить *
 */
$options = __DIR__.'/../config/options.php';

if(file_exists($options)) {
    require_once $options;
} else {
    exit("Не задан файл config/options.php");
}

// Получаем параметры конфигурации
$config = require_once __DIR__.'/../config/app.php';

// В конфигурации приложения обязательно должены быть заданы настройки базы данных
if(!isset($config->db)) {
    exit('В конфигурации не заданы настройки базы данных');
}

// В конфигурации приложения обязательно должен быть задан url приложения
if(!isset($config->app_url)) {
    exit('В конфигурации не задан url приложения');
}

$app = new Application($config);

$app->withAssetsLogs();

return $app->getContainer();
