<?php

namespace Base\Foundation;

use Base\Support\DB\DB;
use Base\Support\DB\DBconnection;
use Base\Support\Request;

class Application
{
    private DBconnection $dbConnection;
    private object $config;
    private object $request;


    public function __construct(object $config)
    {
        // В конфигурации приложения обязательно должены быть заданы настройки базы данных
        if(!isset($config->db)) {
            throw new \Exception('В конфигурации не заданы настройки базы данных');
        }
        $this->dbConnection = new DBconnection($config->db);
        
        // Если в конфигурации приложения не заданы некоторые параметры, берём дефолтные
        $this->config = (new Options($this->config))->config;
        
        // Определяем настройки, соответствующие запросу
        $method = filter_input(INPUT_SERVER, 'REQUEST_METHOD');
        $uri = trim(parse_url(filter_input(INPUT_SERVER, 'REQUEST_URI'), PHP_URL_PATH), '/');
        $this->request = (new Request($this->config, $method, $uri))->request;
    }
    
    /**
     * Возвращает окончательный массив настоек для приложения
     * 
     * @return array
     */
    public function getStorage(): array
    {
        return [
            'db' => new DB($this->dbConnection),
            'config' => $this->config,
            'request' => $this->request
        ];
    }
}
