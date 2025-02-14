<?php
require_once __DIR__ . '/ConnessioneDB.php';  // Connessione al DB

class Utente {

    // Metodo per verificare se l'email esiste nel database
    public function verificaCredenziali($email) {
        // Ottieni la connessione al database
        $conn = ConnessioneDB::getInstance();

        // Prepara la query per cercare l'utente per email
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // Ottieni il risultato
        $result = $stmt->get_result();

        // Se l'utente esiste, ritorna i suoi dati
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Restituisce i dati dell'utente
        }

        // Se l'utente non esiste, ritorna false
        return false;
    }

    // Metodo per ottenere un utente in base all'id
    public function getUserById($id) {
        // Ottieni la connessione al database
        $conn = ConnessioneDB::getInstance();

        // Prepara la query per cercare l'utente per id
        $stmt = $conn->prepare("SELECT * FROM utenti WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        // Ottieni il risultato
        $result = $stmt->get_result();

        // Se l'utente esiste, ritorna i suoi dati
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Restituisce i dati dell'utente
        }

        // Se l'utente non esiste, ritorna false
        return false;
    }

    // Metodo per creare un nuovo utente
    public function creaUtente($nome, $email, $password, $eta, $livello_esperienza) {
        $conn = ConnessioneDB::getInstance();

        // Hash della password
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        // Prepara la query di inserimento
        $stmt = $conn->prepare("INSERT INTO utenti (nome, email, password, eta, livello_esperienza) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $nome, $email, $passwordHash, $eta, $livello_esperienza);
        $stmt->execute();
    }

    // Metodo per verificare se l'email esiste già nel database
    public function emailEsistente($email) {
        $conn = ConnessioneDB::getInstance();

        $stmt = $conn->prepare("SELECT * FROM utenti WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Se l'email esiste, ritorna true
        return $result->num_rows > 0;
    }
}
?>
