<?php
require_once __DIR__ . '/../model/ConnessioneDB.php'; // Includi il file ConnessioneDB.php

class CommentiController {

    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance(); // Usa la connessione esistente
    }

    // Metodo per aggiungere un commento
    public function aggiungiCommento($escursione_id, $user_id, $commento) {
        $query = "INSERT INTO commenti (escursione_id, user_id, commento, data) VALUES (?, ?, ?, NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('iis', $escursione_id, $user_id, $commento);
        return $stmt->execute();
    }

    // Aggiungi il "Mi Piace"
    public function addLike($user_id, $commento_id) {
        $query = "UPDATE commenti SET mi_piace = mi_piace + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
    }

    // Rimuovi il "Mi Piace"
    public function removeLike($user_id, $commento_id) {
        $query = "UPDATE commenti SET mi_piace = mi_piace - 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
    }

    // Aggiungi il "Non Mi Piace"
    public function addDislike($user_id, $commento_id) {
        $query = "UPDATE commenti SET non_mi_piace = non_mi_piace + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
    }

    // Rimuovi il "Non Mi Piace"
    public function removeDislike($user_id, $commento_id) {
        $query = "UPDATE commenti SET non_mi_piace = non_mi_piace - 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
    }

    // Verifica se l'utente ha messo "Mi Piace"
    public function hasLiked($user_id, $commento_id) {
        $query = "SELECT voto FROM voti WHERE user_id = ? AND commento_id = ? AND voto = 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $user_id, $commento_id);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    // Verifica se l'utente ha messo "Non Mi Piace"
    public function hasDisliked($user_id, $commento_id) {
        $query = "SELECT voto FROM voti WHERE user_id = ? AND commento_id = ? AND voto = -1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ii', $user_id, $commento_id);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    // Ottieni i contatori Mi Piace e Non Mi Piace
    public function getLikeDislikeCount($commento_id) {
        $query = "SELECT mi_piace, non_mi_piace FROM commenti WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    // Recupera i commenti per una determinata escursione
    public function getCommenti($escursione_id) {
        $query = "SELECT * FROM commenti WHERE escursione_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('i', $escursione_id);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
