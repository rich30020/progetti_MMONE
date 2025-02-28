<?php

namespace App\Models;

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'DBConfig.php';

use Config\DBConfig;
use PDO;
use PDOException;

class MySql extends DBConfig
{
    public ?PDO $connection;
    public $dataSet;
    private ?string $sqlQuery;

    protected string $databaseName = "";
    protected string $hostName = "";
    protected string $userName = "";

    public function __construct()
    {
        $this->connection = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;

        $dbParams = new DBConfig();
        $this->databaseName = (string) $dbParams->dbName;
        $this->hostName = (string) $dbParams->serverName;
        $this->userName = (string) $dbParams->userName;
        $dbParams = NULL;
    }

    public function dbConnect(): PDO
    {
        $this->connection = new PDO("mysql:host=$this->hostName;dbname=$this->databaseName", $this->userName);
        return $this->connection;
    }

    public function dbDisconnect(): void
    {
        $this->connection = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->databaseName = "";
        $this->hostName = "";
        $this->userName = "";
    }

    public function selectAll(string $tableName)
    {
        $this->sqlQuery = 'SELECT * FROM ' . $this->databaseName . '.' . $tableName;
        $this->dataSet = $this->connection->query($this->sqlQuery);
        $this->sqlQuery = NULL;
        if ($this->dataSet->rowCount() < 0) {
            return -1;
        } else {
            return $this->dataSet;
        }
    }

    public function query(string $query)
    {
        $this->sqlQuery = $query;
        $this->dataSet = $this->connection->query($this->sqlQuery);
        $this->sqlQuery = NULL;
        if ($this->dataSet->rowCount() <= 0) {
            return -1;
        } else {
            $response = $this->dataSet->fetchAll();
            return $response;
        }
    }

    public function delete(string $query)
    {
        $this->sqlQuery = $query;
        try {
            $this->dataSet = $this->connection->exec($this->sqlQuery);
        } catch (PDOException $e) {
            return -1;
        }
        $this->sqlQuery = NULL;
        return $this->dataSet;
    }

    public function update(string $query)
    {
        $this->sqlQuery = $query;
        try {
            $this->dataSet = $this->connection->exec($this->sqlQuery);
        } catch (PDOException $e) {
            return -1;
        }
        $this->sqlQuery = NULL;
        return $this->dataSet;
    }

    public function insert(string $query): int
    {
        $this->sqlQuery = $query;
        try {
            $this->dataSet = $this->connection->exec($this->sqlQuery);
        } catch (PDOException $e) {
            return -1;
        }
        $this->sqlQuery = NULL;
        return (int) $this->connection->lastInsertId();
    }
}
