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

    // Modifica: inizializzazione delle variabili con stringhe vuote
    protected string $databaseName = "";  // Inizializzato con una stringa vuota
    protected string $hostName = "";      // Inizializzato con una stringa vuota
    protected string $userName = "";      // Inizializzato con una stringa vuota

    public function __construct()
    {
        $this->connection = NULL;
        $this->sqlQuery = NULL;
        $this->dataSet = NULL;

        $dbParams = new DBConfig();
        // Modifica: Esplicita la conversione a stringa per evitare problemi con tipi
        $this->databaseName = (string) $dbParams->dbName; // Tipo esplicitamente convertito
        $this->hostName = (string) $dbParams->serverName; // Tipo esplicitamente convertito
        $this->userName = (string) $dbParams->userName;   // Tipo esplicitamente convertito
        $dbParams = NULL;
    }

    // Modifica: Aggiunta dichiarazione del tipo di ritorno `PDO`
    public function dbConnect(): PDO
    {
        $this->connection = new PDO("mysql:host=$this->hostName;dbname=$this->databaseName", $this->userName);
        return $this->connection;
    }

    // Modifica: Aggiunta dichiarazione del tipo di ritorno `void` per il metodo senza valore di ritorno
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

    // Modifica: Aggiunta dichiarazione del tipo di ritorno `int` e gestione dell'ID dell'ultimo inserimento
    public function insert(string $query): int
    {
        $this->sqlQuery = $query;
        try {
            $this->dataSet = $this->connection->exec($this->sqlQuery);
        } catch (PDOException $e) {
            return -1;
        }
        $this->sqlQuery = NULL;
        // Modifica: Ritorna l'ID dell'ultima riga inserita come `int`
        return (int) $this->connection->lastInsertId(); // Conversione esplicita in intero
    }
}
