<?php

namespace Tests\Support\DB;

use Base\Support\DB\DB;

class DBTest extends DBCase
{
    private const ONE_VALUE_QUERY = <<<SQL
            SELECT name FROM unnest(ARRAY[1, 2 ,3], ARRAY['aa', 'bb', 'ccc']) AS _(id, name)
            WHERE _.id = :id;
        SQL;
    
    private const ONE_LINE_QUERY = <<<SQL
            SELECT * FROM unnest(ARRAY[1, 2 ,3], ARRAY['aa', 'bb', 'ccc']) AS _(id, name)
            WHERE _.id = :id;
        SQL;
    
    private const QUERY_WITH_MULTIPLE_LINES = <<<SQL
            SELECT * FROM unnest(ARRAY[1, 2 ,3], ARRAY['aa', 'bb', 'ccc']) AS _(id, name)
        SQL;
    
    public function test_success_execute(): void
    {
        $this->assertNull($this->db->execute(self::ONE_VALUE_QUERY, ['id' => 1]));
    }
    
    public function test_fail_execute(): void
    {
        $this->expectException(\PDOException::class);
        $this->assertNull($this->db->execute(self::ONE_VALUE_QUERY, ['title' => 0])); // Неверный параметр
    }

    public function test_selectValue_empty_string()
    {
        $this->assertEquals('', $this->db->selectValue(self::ONE_VALUE_QUERY, ['id' => 0]));
    }

    public function test_selectValue_returns_value()
    {
        $this->assertEquals(4, $this->db->selectValue("SELECT :n1::int + :n2::int", [
            'n1' => 1,
            'n2' => 3
        ]));
    }

    public function test_selectValue_with_multiple_fields()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(DB::ERR_MESSAGE_MULTIPLE_FIELDS);
        $this->db->selectValue("SELECT :n1, :n2", [
            'n1' => 1,
            'n2' => 3
        ]);
    }

    public function test_selectValue_with_multiple_lines()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(DB::ERR_MESSAGE_MULTIPLE_LINES);
        $this->db->selectValue("SELECT unnest(ARRAY[:n1, :n2])", [
            'n1' => 1,
            'n2' => 3
        ]);
    }

    public function test_selectObject_correct_request()
    {
        $this->assertEquals((object) ['id' => 2, 'name' => 'bb'], $this->db->selectObject(self::ONE_LINE_QUERY, [':id' => 2]));

        // Если запрос возвращает несколько строк, метод selectObject вернёт объект первой строки
        $this->assertEquals((object) ['id' => 1, 'name' => 'aa'], $this->db->selectObject(self::QUERY_WITH_MULTIPLE_LINES, []));
    }

    public function test_selectObject_request_with_sql_injection()
    {
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('неверный синтаксис для типа integer: "(SELECT 2)"');
        $this->db->selectObject(self::ONE_LINE_QUERY, ['id' => '(SELECT 2)']);
    }

    public function test_selectObjects_correct_request()
    {
        $this->assertEquals([(object) ['id' => 3, 'name' => 'ccc']], $this->db->selectObjects(self::ONE_LINE_QUERY, ['id' => 3]));
        $this->assertEquals([
                (object) ['id' => 1, 'name' => 'aa'],
                (object) ['id' => 2, 'name' => 'bb'],
                (object) ['id' => 3, 'name' => 'ccc']
            ], $this->db->selectObjects(self::QUERY_WITH_MULTIPLE_LINES, []));
    }

    public function test_selectObjects_request_with_sql_injection()
    {
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('неверный синтаксис для типа integer: "(SELECT 3)"');
        $this->db->selectObjects(self::ONE_LINE_QUERY, ['id' => '(SELECT 3)']);
    }
}
