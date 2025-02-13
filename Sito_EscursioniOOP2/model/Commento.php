<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Commento {

    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite PDO
        $this->db = ConnessioneDB::getInstance();
    }

    // Aggiungi un commento
    public function aggiungiCommento($user_id, $escursione_id, $commento) {
        $query = "INSERT INTO commenti (user_id, escursione_id, commento) VALUES (:user_id, :escursione_id, :commento)";
        $stmt = $this->db->prepare($query);
        
        // Associa i parametri
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':escursione_id', $escursione_id, PDO::PARAM_INT);
        $stmt->bindParam(':commento', $commento, PDO::PARAM_STR);

        // Esegui la query
        return $stmt->execute();
    }

    // Recupera tutti i commenti per una specifica escursione
    public function getCommenti($escursione_id) {
        $query = "SELECT * FROM commenti WHERE escursione_id = :escursione_id";
        $stmt = $this->db->prepare($query);
        
        // Associa il parametro
        $stmt->bindParam(':escursione_id', $escursione_id, PDO::PARAM_INT);

        $stmt->execute();

        // Restituisci tutti i commenti come array associativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
