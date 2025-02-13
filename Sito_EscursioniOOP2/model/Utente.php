<?php
require_once __DIR__ . '/ConnessioneDB.php';

class Utente {
    private $db;

    public function __construct() {
        // Ottieni direttamente l'istanza della connessione PDO
        $this->db = ConnessioneDB::getInstance(); 
    }

    public function verificaCredenziali($email, $password) {
        $query = "SELECT * FROM utenti WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $utente = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utente && password_verify($password, $utente['password'])) {
            return $utente;
        }
        return false;
    }

    public function emailEsistente($email) {
        $query = "SELECT * FROM utenti WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function creaUtente($nome, $email, $password, $eta, $livello_esperienza) {
        $query = "INSERT INTO utenti (nome, email, password, eta, livello_esperienza) 
                  VALUES (:nome, :email, :password, :eta, :livello_esperienza)";
        $stmt = $this->db->prepare($query);
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password_hash, PDO::PARAM_STR);
        $stmt->bindParam(':eta', $eta, PDO::PARAM_INT);
        $stmt->bindParam(':livello_esperienza', $livello_esperienza, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getUserById($userId) {
        $query = "SELECT nome FROM utenti WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
