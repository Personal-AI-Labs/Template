<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    /**
     * @var ?Database The single, static instance of the Database wrapper class.
     */
    private static ?Database $instance = null;

    /**
     * @var PDO The actual PDO database connection object.
     */
    private PDO $pdo;

    /**
     * The constructor is private. It creates the PDO connection and stores it.
     */
    private function __construct() {
        $host = $_ENV['POSTGRES_HOST'];
        $db   = $_ENV['POSTGRES_DB'];
        $user = $_ENV['POSTGRES_USER'];
        $pass = $_ENV['POSTGRES_PASSWORD'];

        $dsn = "pgsql:host=$host;dbname=$db";
        $options = [
            // Use the global PDO class constants
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            // Use the global PDO class to instantiate
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) { // Use the global PDOException
            // In a real app, you might log this error instead of exposing details
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    /**
     * The public static "getter" for the single instance of this Database class.
     *
     * @return Database The single Database wrapper instance.
     */
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * A proxy magic method to pass calls to the internal PDO object.
     */
    public function __call($method, $args) {
        return call_user_func_array([$this->pdo, $method], $args);
    }

    /**
     * Executes a prepared statement and returns a single row.
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes a prepared statement and returns all rows.
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Executes a prepared statement and returns a single column from the first row.
     */
    public function fetchColumn($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /**
     * Fetches a single record from the database as a standard generic object.
     *
     * @param string $sql The SQL query.
     * @param array $params The parameters to bind to the query.
     * @return object|false The record as an object, or false if not found.
     */
    public function fetchOneObject(string $sql, array $params = []): object|false
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * Fetches a single record and populates an instance of a given class.
     *
     * @param string $sql The SQL query.
     * @param string $className The name of the class to instantiate.
     * @param array $params The parameters to bind to the query.
     * @return mixed An instance of the class, or false if not found.
     */
    public function fetchIntoClass(string $sql, string $className, array $params = []): mixed
    {
        $stmt = $this->pdo->prepare($sql);
        // Tell PDO to return a new instance of the specified class
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $className, [$this]);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Executes a prepared statement for INSERT, UPDATE, or DELETE.
     */
    public function execute($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Public clone method to prevent cloning of the instance, satisfying PHP's visibility requirement.
     */
    public function __clone() {
        // Throwing an exception is a robust way to prevent cloning
        throw new \Exception("Cannot clone a singleton.");
    }

    /**
     * Public wakeup method to prevent unserializing, satisfying PHP's visibility requirement.
     */
    public function __wakeup() {
        // Throwing an exception is a robust way to prevent unserialization
        throw new \Exception("Cannot unserialize a singleton.");
    }
}