<?php

require_once __DIR__.'/options.php';

/**
 * Настройки базы данных
 */
$dsn = DB_CONNECTION.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_DATABASE;

return (object) [
    'dsn' => $dsn,
    'username' => DB_USERNAME,
    'password' => DB_PASSWORD,
    'charset' => 'utf8',
];
