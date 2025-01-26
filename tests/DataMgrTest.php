<?php

namespace Test;

use PHPUnit\Framework\TestCase;
use PDO;

class DataMgrTest extends TestCase
{
    private $dataMgr;

    protected function setUp(): void
    {
        $this->dataMgr = new class('TestTable') extends DataMgr {
            protected function map($item)
            {
                return $item;
            }
        };

        $this->dataMgr->db->exec("
            IF OBJECT_ID('TestTable', 'U') IS NOT NULL
                DROP TABLE TestTable;
            CREATE TABLE TestTable (id INT IDENTITY(1,1), name NVARCHAR(255), value NVARCHAR(255));
        ");
    }

    protected function tearDown(): void
    {
        $this->dataMgr->db->exec("
            IF OBJECT_ID('TestTable', 'U') IS NOT NULL
                DROP TABLE TestTable;
        ");
    }

    public function testSaveContents(): void
    {
        $data = ['name' => 'TestName', 'value' => 'TestValue'];
        $saveContents = function () use ($data) {
            $this->saveContents($data);
        };

        $saveContents->call($this->dataMgr);

        $result = $this->dataMgr->db->query("SELECT * FROM TestTable")->fetchAll();
        $this->assertCount(1, $result);
        $this->assertSame('TestName', $result[0]['name']);
        $this->assertSame('TestValue', $result[0]['value']);
    }

    public function testLoadContents(): void
    {
        $this->dataMgr->db->exec("INSERT INTO TestTable (name, value) VALUES ('Name1', 'Value1'), ('Name2', 'Value2')");

        $loadContents = function () {
            return $this->loadContents('stdClass', self::ASC);
        };

        $result = $this->dataMgr->db->query("SELECT * FROM TestTable")->fetchAll(PDO::FETCH_ASSOC);
        var_dump($result);

        $this->assertCount(2, $result);
        $this->assertSame('Name1', $result[0]['name']);
        $this->assertSame('Value1', $result[0]['value']);
    }

    public function testDeleteContents(): void
    {
        $this->dataMgr->db->exec("INSERT INTO TestTable (name, value) VALUES ('Name3', 'Value3')");
        $id = $this->dataMgr->db->lastInsertId();

        $deleteContents = function () use ($id) {
            $this->deleteContents((int)$id);
        };

        $deleteContents->call($this->dataMgr);

        $result = $this->dataMgr->db->query("SELECT * FROM TestTable")->fetchAll();
        $this->assertCount(0, $result);
    }

}
