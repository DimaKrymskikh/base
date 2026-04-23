<?php

namespace Base\Support\DB;

/**
 * Класс с методами, выполняющими запрос в базу данных.
 */
final class DB
{
    public const ERR_MESSAGE_FAIL_EXECUTE = "При выполнении подготовленного запроса произошла ошибка.";
    public const ERR_MESSAGE_MULTIPLE_FIELDS = "В запросе извлекается несколько полей.";
    public const ERR_MESSAGE_MULTIPLE_LINES = "Запрос извлекает более одной строки.";
    
    private \PDO $dbh;
    
    public function __construct(DBhandle $dbHandle)
    {
        $this->dbh = $dbHandle->dbh;
    }

    /**
     * Применяется при выполнении запросов INSERT, UPDATE или DELETE без извлечения данных из базы.
     * 
     * @param string $sql
     * @param array $params
     * @return void
     */
    public function execute(string $sql, array $params): void
    {
        $this->prepareAndExecute($sql, $params);
    }

    /**
     * Из базы данных извлекается одно поле.
     * Нужно следить за тем, чтобы запрос $sql возвращал одно поле и одну строку.
     * 
     * @param string $sql - строка запроса
     * @param array $params - параметры запроса
     * @return string
     * @throws Exception
     */
    public function selectValue(string $sql, array $params): string
    {
        $sth = $this->prepareAndExecute($sql, $params);
        $result = $sth->fetch(\PDO::FETCH_NUM);
        
        if (!$result) {
            // Если запрос не извлёк ни одной строки, возвращаем пустую строку
            return '';
        }
        
        if(count($result) > 1) {
            throw new \Exception(self::ERR_MESSAGE_MULTIPLE_FIELDS);
        }

        // Пытаемся извлечь ещё одну строку. Если получается, то бросаем исключение
        if ($sth->fetch(\PDO::FETCH_NUM)) {
            throw new \Exception(self::ERR_MESSAGE_MULTIPLE_LINES);
        }

        // Если запрос извлёк одну строку с одной величиной, возвращаем величину извлечённого поля.
        return $result[0];
    }

    /**
     * Из базы данных извлекается одна строка в виде std-объекта.
     * 
     * @param string $sql
     * @param array $params
     * @return object
     */
    public function selectObject(string $sql, array $params): object
    {
        $sth = $this->prepareAndExecute($sql, $params);
        $result = $sth->fetch(\PDO::FETCH_OBJ);
        return $result ?: (object)[];
    }

    /**
     * Из базы данных извлекается несколько строк в виде массива std-объектов.
     * 
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function selectObjects(string $sql, array $params): array
    {
        $sth = $this->prepareAndExecute($sql, $params);
        return $sth->fetchAll(\PDO::FETCH_OBJ) ?: [];
    }
    
    private function prepareAndExecute(string $sql, array $params): \PDOStatement
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($params);
        
        return $sth;
    }
}
