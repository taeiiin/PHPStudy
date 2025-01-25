<?php

namespace Test;

use PDO;
use PDOException;
use Dotenv\Dotenv;

abstract class DataMgr
{
    public const ASC = 1;
    public const DESC = 2;
    protected PDO $db;
    protected string $table;
    
    public function __construct(string $table)
    {
        $this->table = $table;

        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();

        $host = $_ENV['DB_HOST'];
        $port = $_ENV['DB_PORT'];
        $dbname = $_ENV['DB_DATABASE'];
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];

        try {
            $this->db = new PDO("sqlsrv:Server=$host,$port;Database=$dbname", $username, $password);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            error_log("Database connection successful.");
        } catch (PDOException $e) {
            error_log("Database connection failed.");
        }
    }

    protected function loadContents(string $class, int $order = self::DESC): array
    {
        $orderBy = $order === self::ASC ?'ASC' : 'DESC';
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY id {$orderBy}");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); //!

        return array_map(function ($item) use ($class) {
            if (!method_exists($class, 'of')) {
                return new $class($item);
            }
            return $class::of($item);

        }, $rows);
    }

    protected function saveContents(array $data): void
    {
        unset($data['id']);
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(fn($key) => ":$key", array_keys($data)));
        $stmt = $this->db->prepare("INSERT INTO {$this->table} ($columns) VALUES ($placeholders)");
        $stmt->execute($data); //!
    }

    protected function deleteContents(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    abstract protected function map($item);
}