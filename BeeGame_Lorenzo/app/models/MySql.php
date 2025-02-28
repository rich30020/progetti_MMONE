<?php

namespace App\Models;

require_once dirname(dirname(__DIR__)) . '\config\DBConfig.php';

use Config\DBConfig;
use PDO;
use PDOException;

class MySql extends DBConfig
{
    public ?PDO $connection;
    public $dataSet;
    private ?string $sqlQuery;

    protected string $databaseName;
    protected string $hostName;
    protected string $userName;
    //protected $userPassword;

    public function __construct()
    {
        $this->connection = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;

        $dbParams = new DBConfig();
        $this->databaseName = $dbParams->dbName;
        $this->hostName = $dbParams->serverName;
        $this->userName = $dbParams->userName;
        $dbParams = NULL;
    }

    public function dbConnect()
    {
        $this->connection = new PDO("mysql:host=$this->hostName;dbname=$this->databaseName", $this->userName);
        return $this->connection;
    }

    public function dbDisconnect()
    {
        $this->connection = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;
        $this->databaseName = NULL;
        $this->hostName = NULL;
        $this->userName = NULL;
        //$this -> passCode = NULL;
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
        }catch (PDOException $e) {
            return -1;
        }
        $this->sqlQuery = NULL;
        return $this->connection->lastInsertId();
    }
}
