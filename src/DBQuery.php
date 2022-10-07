<?php

namespace base;

use controllers\ErrorController;
use base\PrintException;

/**
 * Класс для взаимодействия с базой данных
 * @property \PDO $dbh - соединение с базой
 */
class DBQuery 
{
    private \PDO $dbh;

    public function __construct(object $db) 
    {
        // Выполняется постоянное соединение с базой
        try {
            $this->dbh = new \PDO($db->dsn, $db->username, $db->password, [\PDO::ATTR_PERSISTENT => true]);
        } catch(\PDOException $e) {
            echo (new ErrorController())->indexAction("Ошибка соединения с базой данны: " . $e->getMessage());
            exit();
        }
        // Установить исключения при ошибках в базе данных
        $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * Применяется при выполнении запросов INSERT или UPDATE без извлечения данных из базы
     * @param string $sql
     * @param array $param
     * @return void
     */
    public function execute(string $sql, array $param): void 
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($param);
    }
    
    /**
     * Из базы данных извлекается одно поле.
     * Нужно следить за тем, чтобы запрос $sql возвращал одно поле и одну строку
     * @param string $sql - строка запроса
     * @param array $params - параметры запроса
     * @return string
     * @throws PrintException
     */
    public function selectValue(string $sql, array $params): string
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
        $result = $sth->fetch(\PDO::FETCH_NUM);
        
        if (is_array($result) && count($result) !== 1) {
            throw new PrintException("В запросе извлекается несколько полей");
        }
        
        // Пытаемся извлечь ещё одну строку. Если получается, то бросаем исключение
        if ($sth->fetch(\PDO::FETCH_NUM)) {
            throw new PrintException("Запрос извлекает более одной строки");
        }
        
        // Если запрос извлёк одну строку, возвращаем величину извлечённого поля.
        // Если запрос не извлёк ни одной строки, возвращаем пустую строку
        return isset($result[0]) ? $result[0] : '';
    }
    
    /**
     * Из базы данных извлекается одна строка в виде объекта
     * @param string $sql
     * @param array $param
     * @return object|null
     */
    public function selectObject(string $sql, array $param): object 
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($param);
        $result = $sth->fetch(\PDO::FETCH_OBJ);
        return $result ?: (object)[];
    }
    
    /**
     * Из базы данных извлекается несколько строк в виде массива объектов
     * @param string $sql
     * @param array $param
     * @return array
     */
    public function selectObjects(string $sql, array $param): array
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($param);
        return $sth->fetchAll(\PDO::FETCH_OBJ) ?: [];
    }
}
