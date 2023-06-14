<?php

use PHPUnit\Framework\TestCase;
use Base\DBQuery;

class DBQueryTest extends TestCase
{
    private static DBQuery $db;

    public static function setUpBeforeClass(): void
    {
        $db = require_once __DIR__ . '/../config/db.php';
        self::$db = new DBQuery($db);
    }

    public function testSelectValue()
    {
        $this->assertEquals(4, self::$db->selectValue("SELECT :n1::int + :n2::int", [
            'n1' => 1,
            'n2' => 3
        ]));
    }

    public function testExceptionSelectValueMultipleFields()
    {
        $this->expectExceptionMessage(DBQuery::ERR_MESSAGE_MULTIPLE_FIELDS);
        self::$db->selectValue("SELECT :n1, :n2", [
            'n1' => 1,
            'n2' => 3
        ]);
    }

    public function testExceptionSelectValueMultipleLines()
    {
        $this->expectExceptionMessage(DBQuery::ERR_MESSAGE_MULTIPLE_LINES);
        self::$db->selectValue("SELECT unnest(ARRAY[:n1, :n2])", [
            'n1' => 1,
            'n2' => 3
        ]);
    }

    public function testSelectObject()
    {
        $this->assertEquals((object) ['a' => 1, 'b' => 3], self::$db->selectObject("SELECT :n1 AS a, :n2 AS b", [
            'n1' => 1,
            'n2' => 3
        ]));

        // Если запрос возвращает несколько строк, метод selectObject вернёт объект первой строки
        $this->assertEquals((object) ['a' => 1, 'b' => 4], self::$db->selectObject("SELECT * FROM unnest(ARRAY[1, :n1 ,3], ARRAY[4, 5, :n2]) AS _(a, b)", [
            'n1' => 2,
            'n2' => 6
        ]));
    }

    public function testSelectObjects()
    {
        $this->assertEquals([
            (object) ['a' => 1, 'b' => 4],
            (object) ['a' => 2, 'b' => 5],
            (object) ['a' => 3, 'b' => 6],
        ], self::$db->selectObjects("SELECT * FROM unnest(ARRAY[1, :n1 ,3], ARRAY[4, 5, :n2]) AS _(a, b)", [
            'n1' => 2,
            'n2' => 6
        ]));
    }
}
