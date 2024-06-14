<?php

namespace Base\Support\DB;

readonly class DBconnection
{
    public \PDO $dbh;

    public function __construct(object $db)
    {
        // Выполняется постоянное соединение с базой
        try {
            $this->dbh = new \PDO($db->dsn, $db->username, $db->password, [\PDO::ATTR_PERSISTENT => true]);
        } catch(\PDOException $e) {
            echo "Ошибка соединения с базой данны: " . $e->getMessage();
            exit();
        }
        // Установить исключения при ошибках в базе данных
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
