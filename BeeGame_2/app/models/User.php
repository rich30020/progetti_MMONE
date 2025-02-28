<?php

namespace App\Models;

require_once 'MySql.php';

class User
{
    protected int $id;
    protected string $userName;

    // Funzione per connettersi al DB e restituire l'oggetto MySql
    private function dbConnect(): MySql
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        return $mysql;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    // Crea un nuovo utente e restituisce l'ID dell'utente creato o -1 in caso di errore
    public function create(string $userName): int
    {
        $mysql = $this->dbConnect(); // Usato il metodo dbConnect() per evitare ripetizioni

        // Verifica se l'utente esiste già prima di crearlo
        $checkQuery = "SELECT id FROM `users` WHERE user_name = '$userName'";
        $result = $mysql->query($checkQuery);

        if (!empty($result)) {
            // L'utente esiste già
            return -1;
        }

        $query = "INSERT INTO `users`(`user_name`) VALUES ('$userName')";
        $id = $mysql->insert($query);

        if ($id == -1) {
            return -1; // Errore nell'inserimento
        }

        $this->setUserName($userName);
        $this->setId($id); // Imposta l'ID dell'utente appena creato
        return $this->getId();
    }

    // Legge un utente tramite il suo ID
    public function read(int $id): void
    {
        $mysql = $this->dbConnect(); // Usato il metodo dbConnect() per evitare ripetizioni
        $query = "SELECT * FROM `users` WHERE id = $id";
        $results = $mysql->query($query);

        if (!empty($results)) {
            $this->setUserName($results[0]['user_name']);
            $this->setId($results[0]['id']);
        } else {
            $this->setId(-1); // Utente non trovato
        }
    }

    // Legge un utente tramite il nome utente
    public function readByUsername(string $userName): ?User
    {
        $mysql = $this->dbConnect(); // Usato il metodo dbConnect() per evitare ripetizioni
        $query = "SELECT * FROM `users` WHERE user_name = '$userName'";
        $results = $mysql->query($query);

        if (!empty($results)) {
            $this->setUserName($results[0]['user_name']);
            $this->setId($results[0]['id']);
            return $this;
        }

        return null; // Se non trovato, ritorna null per indicare errore
    }
}
