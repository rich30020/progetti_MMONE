<?php
require_once __DIR__ . '/../model/ConnessioneDB.php';

class Utente {
    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite ConnessioneDB::getConnessione()
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    // Metodo per ottenere i dettagli di un utente tramite ID
    public function getUserById($userId) {
        try {
            $query = "SELECT * FROM utenti WHERE id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("i", $userId); // "i" indica che il parametro è un intero
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nel recupero dell'utente: " . $e->getMessage());
            return null;
        }
    }

    // Metodo per aggiornare il profilo di un utente
    public function updateProfile($userId, $nome, $email, $eta, $livello_esperienza) {
        try {
            // Prepara la query SQL per aggiornare il profilo
            $query = "UPDATE utenti SET nome = ?, email = ?, eta = ?, livello_esperienza = ? WHERE id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }

            // Bind dei parametri per la query SQL
            $stmt->bind_param('ssiii', $nome, $email, $eta, $livello_esperienza, $userId);

            // Esegui la query
            $stmt->execute();

            // Verifica se l'aggiornamento ha avuto successo (se almeno una riga è stata aggiornata)
            return $stmt->affected_rows > 0;
        } catch (Exception $e) {
            error_log("Errore nell'aggiornamento del profilo: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per verificare le credenziali dell'utente tramite email
    public function verificaCredenziali($email) {
        try {
            $query = "SELECT * FROM utenti WHERE email = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("s", $email); // "s" indica che il parametro è una stringa
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nella verifica delle credenziali: " . $e->getMessage());
            return null;
        }
    }

    // Metodo per verificare se un'email è già registrata
    public function emailEsistente($email) {
        try {
            $query = "SELECT COUNT(*) FROM utenti WHERE email = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("s", $email); // "s" indica che il parametro è una stringa
            $stmt->execute();
            return $stmt->get_result()->fetchColumn() > 0;
        } catch (Exception $e) {
            error_log("Errore nel controllo dell'email esistente: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per creare un nuovo utente
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
