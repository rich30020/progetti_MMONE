<?php

require_once __DIR__ . '/../model/ConnessioneDB.php';

class CommentiController {
    private $conn;

    public function __construct() {
        $this->conn = ConnessioneDB::getInstance()->getConnessione();
    }

    // Metodo per ottenere tutti i commenti di una specifica escursione
    public function getCommenti($escursione_id) {
        $sql = "SELECT c.id, c.user_id, c.commento, c.mi_piace, c.non_mi_piace, u.nome, c.data
                FROM commenti c
                JOIN utenti u ON c.user_id = u.id
                WHERE c.escursione_id = ?
                ORDER BY c.data DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $escursione_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $commenti = [];
        while ($row = $result->fetch_assoc()) {
            $commenti[] = $row;
        }

        $stmt->close();
        return $commenti;
    }

    // Metodo per aggiungere un commento
    public function aggiungiCommento($escursione_id, $user_id, $commento) {
        $commento = htmlspecialchars($commento); // Prevenzione XSS
        $sql = "INSERT INTO commenti (escursione_id, user_id, commento, data) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iis', $escursione_id, $user_id, $commento);
        $esito = $stmt->execute();
        $stmt->close();
        return $esito;
    }

    // Metodo per aggiungere un "Mi Piace" a un commento
    public function aggiungiMiPiace($commento_id) {
        return $this->incrementaMiPiace($commento_id);
    }

    // Metodo per aggiungere un "Non Mi Piace" a un commento
    public function aggiungiNonMiPiace($commento_id) {
        return $this->incrementaNonMiPiace($commento_id);
    }

    // Metodo per incrementare il contatore di "Mi Piace"
    public function incrementaMiPiace($commento_id) {
        $sql = "UPDATE commenti SET mi_piace = mi_piace + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $esito = $stmt->execute();
        $stmt->close();
        return $esito;
    }

    // Metodo per incrementare il contatore di "Non Mi Piace"
    public function incrementaNonMiPiace($commento_id) {
        $sql = "UPDATE commenti SET non_mi_piace = non_mi_piace + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $esito = $stmt->execute();
        $stmt->close();
        return $esito;
    }

    // Metodo per ottenere un commento specifico tramite l'ID
    public function getCommentoById($commento_id) {
        $sql = "SELECT c.id, c.user_id, c.commento, c.mi_piace, c.non_mi_piace, u.nome 
                FROM commenti c
                JOIN utenti u ON c.user_id = u.id
                WHERE c.id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();

        $result = $stmt->get_result();
        $commento = $result->fetch_assoc();

        $stmt->close();
        return $commento;
    }
    // Metodo per verificare se l'utente ha già votato
    public function haGiaVotato($commento_id, $user_id) {
        $sql = "SELECT utenti_votati FROM commenti WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $commento = $result->fetch_assoc();

        $utenti_votati = explode(',', $commento['utenti_votati']);
        return in_array($user_id, $utenti_votati); // Verifica se l'utente ha già votato
    }

}
?>
