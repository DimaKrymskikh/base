<?php

namespace Base\Support\DB;

/**
 * Класс, выполняющий соединение с базой данных.
 */
final class DBhandle
{
    private static self|null $instance = null;

    public readonly \PDO $dbh;
    
    private function __construct()
    {
        $db = config('db');
        
        // Выполняется постоянное соединение с базой
        $this->dbh = new \PDO($db->dsn, $db->username, $db->password, [\PDO::ATTR_PERSISTENT => true]);
        
        // Установить исключения при ошибках в базе данных
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public static function getInstance(): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
