<?php

namespace App\Models;

require_once 'MySql.php';

class User
{
    protected int $id;
    protected string $userName;

    // **Modifica**: Aggiunto il metodo privato dbConnect() per evitare ripetizioni
    // del codice di connessione al database in più metodi
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

    // **Modifica**: La funzione create() ora verifica prima se l'utente esiste già
    // nel database. Se esiste, ritorna -1 senza tentare di inserirlo di nuovo.
    public function create(string $userName): int
    {
        $mysql = $this->dbConnect(); // Usato il metodo dbConnect() per evitare ripetizioni

        // **Modifica**: Verifica se l'utente esiste già prima di tentare l'inserimento
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

    // **Modifica**: La funzione read() ora gestisce il caso in cui l'utente non venga trovato
    // e imposta l'ID a -1 per indicare l'assenza dell'utente.
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

    // **Modifica**: La funzione readByUsername() ora restituisce null se l'utente non viene trovato
    // anziché non fare nulla, rendendo il comportamento più chiaro.
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
