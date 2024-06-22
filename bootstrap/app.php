<?php

use Base\DataTransferObjects\InputServerDto;
use Base\Foundation\Application;
use Base\Support\DB\DB;
use Base\Support\DB\DBconnection;

/**
 * В этом файле должны быть определены параметры соединения с базой данных
 * Нужно скопировать файл options.example.php в options.php и заменить *
 */
$options = __DIR__.'/../config/options.php';

if(file_exists($options)) {
    require_once $options;
} else {
    echo "Не задан файл config/options.php";
    exit;
}

// Получаем параметры конфигурации
$config = require_once __DIR__ . '/../config/app.php';

// В конфигурации приложения обязательно должены быть заданы настройки базы данных
if(!isset($config->db)) {
    throw new \Exception('В конфигурации не заданы настройки базы данных');
}
$dbConnection = new DBconnection($config->db);

$inputServer = new InputServerDto(
    filter_input(INPUT_SERVER, 'REQUEST_METHOD'),
    trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/'),
);

return new Application(new DB($dbConnection), $config, $inputServer);
