<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Commento {

    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite mysqli
        $this->db = ConnessioneDB::getInstance();
    }

    // Aggiungi un commento
    public function aggiungiCommento($user_id, $escursione_id, $commento) {
        $query = "INSERT INTO commenti (user_id, escursione_id, commento) VALUES (?, ?, ?)";
        
        // Prepara la query
        $stmt = $this->db->prepare($query);

        // Controlla se la preparazione è andata a buon fine
        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }

        // Associa i parametri
        $stmt->bind_param("iis", $user_id, $escursione_id, $commento);

        // Esegui la query
        return $stmt->execute();
    }

    // Recupera tutti i commenti per una specifica escursione
    public function getCommenti($escursione_id) {
        $query = "SELECT * FROM commenti WHERE escursione_id = ?";
        
        // Prepara la query
        $stmt = $this->db->prepare($query);

        // Controlla se la preparazione è andata a buon fine
        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }

        // Associa il parametro
        $stmt->bind_param("i", $escursione_id);

        // Esegui la query
        $stmt->execute();

        // Ottieni i risultati
        $result = $stmt->get_result();

        // Restituisci tutti i commenti come array associativo
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Recupera un singolo commento
    public function getCommento($commento_id) {
        $query = "SELECT * FROM commenti WHERE id = ?";
        
        // Prepara la query
        $stmt = $this->db->prepare($query);

        // Controlla se la preparazione è andata a buon fine
        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }

        // Associa il parametro
        $stmt->bind_param("i", $commento_id);

        // Esegui la query
        $stmt->execute();

        // Ottieni i risultati
        $result = $stmt->get_result();

        // Restituisci il commento come array associativo
        return $result->fetch_assoc();
    }
}
?>
