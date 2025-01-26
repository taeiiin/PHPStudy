<?php

namespace Test;

use PHPUnit\Framework\TestCase;

class GuestMgrTest extends TestCase
{
    private $guestMgr;

    protected function setUp(): void
    {
        $this->guestMgr = new GuestMgr();

        $this->guestMgr->db->exec("
            IF OBJECT_ID('Guest', 'U') IS NOT NULL
                DROP TABLE Guest;
            CREATE TABLE Guest (
                id INT IDENTITY(1,1) PRIMARY KEY,
                name NVARCHAR(255) NOT NULL,
                msg NVARCHAR(MAX) NOT NULL,
                createdAt DATETIME NOT NULL DEFAULT GETDATE()
            );
        ");
    }

    protected function tearDown(): void
    {
        $this->guestMgr->db->exec("
            IF OBJECT_ID('Guest', 'U') IS NOT NULL
                DROP TABLE Guest;
        ");
    }


    public function testSaveGuests(): void
    {
        $guest = new Guestbook([
            'name' => 'Tester',
            'msg' => 'Test message'
        ]);

        $this->guestMgr->saveGuests($guest);

        $result = $this->guestMgr->db->query("SELECT * FROM Guest")->fetchAll(\PDO::FETCH_ASSOC);

        $this->assertCount(1, $result);
        $this->assertSame('Tester', $result[0]['name']);
        $this->assertSame('Test message', $result[0]['msg']);
    }

    public function testLoadGuests(): void
    {
        $this->guestMgr->db->exec("
            INSERT INTO Guest (name, msg, createdAt)
            VALUES 
                ('Tester1', 'Message1', GETDATE()),
                ('Tester2', 'Message2', GETDATE())
        ");

        $guests = $this->guestMgr->loadGuests(GuestMgr::ASC);

        $this->assertInstanceOf(Guestbook::class, $guests[0], "Loaded data is not of type Guestbook");
        $this->assertCount(2, $guests);
        $this->assertSame('Tester1', $guests[0]->name);
        $this->assertSame('Message1', $guests[0]->msg);
        $this->assertSame('Tester2', $guests[1]->name);
        $this->assertSame('Message2', $guests[1]->msg);
    }

}
