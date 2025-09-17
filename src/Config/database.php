<?php

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $host = $_ENV['DB_HOST'] ?? 'postgres';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $dbname = $_ENV['DB_NAME'] ?? 'pm_dashboard';
        $username = $_ENV['DB_USER'] ?? 'pm_user';
        $password = $_ENV['DB_PASS'] ?? 'pm_password';

        try {
            $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
            $this->connection = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw new Exception("Database connection failed");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Query failed: " . $e->getMessage() . " SQL: " . $sql);
            throw new Exception("Query execution failed");
        }
    }

    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetch();
    }

    public function insert($table, $data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders}) RETURNING id";

        $stmt = $this->query($sql, $data);
        $result = $stmt->fetch();
        return $result['id'] ?? null;
    }

    public function update($table, $data, $conditions) {
        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);

        $whereClause = [];
        foreach (array_keys($conditions) as $column) {
            $whereClause[] = "{$column} = :where_{$column}";
        }
        $whereClause = implode(' AND ', $whereClause);

        $sql = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";

        $params = $data;
        foreach ($conditions as $key => $value) {
            $params["where_{$key}"] = $value;
        }

        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    public function delete($table, $conditions) {
        $whereClause = [];
        foreach (array_keys($conditions) as $column) {
            $whereClause[] = "{$column} = :{$column}";
        }
        $whereClause = implode(' AND ', $whereClause);

        $sql = "DELETE FROM {$table} WHERE {$whereClause}";
        $stmt = $this->query($sql, $conditions);
        return $stmt->rowCount();
    }

    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollback();
    }
}