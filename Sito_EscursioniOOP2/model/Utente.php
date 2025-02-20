<?php
require_once __DIR__ . '/../model/ConnessioneDB.php';

class Utente {
    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    public function getUserById($userId) {
        try {
            $query = "SELECT * FROM utenti WHERE id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nel recupero dell'utente: " . $e->getMessage());
            return null;
        }
    }

    public function updateProfile($userId, $nome, $email, $eta, $livello_esperienza) {
        try {
            $query = "UPDATE utenti SET nome = ?, email = ?, eta = ?, livello_esperienza = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }

            $stmt->bind_param('ssiii', $nome, $email, $eta, $livello_esperienza, $userId);

            $stmt->execute();

            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Errore nell'aggiornamento del profilo: " . $e->getMessage());
            return false;
        }
    }

    public function verificaCredenziali($email) {
        try {
            $query = "SELECT * FROM utenti WHERE email = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("s", $email); 
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nella verifica delle credenziali: " . $e->getMessage());
            return null;
        }
    }

    public function emailEsistente($email) {
        try {
            $query = "SELECT COUNT(*) FROM utenti WHERE email = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Errore nel controllo dell'email esistente: " . $e->getMessage());
            return false;
        }
    }

    public function creaUtente($nome, $email, $password, $eta, $livello_esperienza) {
        try {
            $query = "INSERT INTO utenti (nome, email, password, eta, livello_esperienza) VALUES (?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param('sssii', $nome, $email, $password, $eta, $livello_esperienza);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Errore nella creazione dell'utente: " . $e->getMessage());
            return false;
        }
    }
}
?>
