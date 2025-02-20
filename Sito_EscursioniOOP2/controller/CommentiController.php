<?php

require_once __DIR__ . '/../model/ConnessioneDB.php';

class CommentiController {
    private $conn;

    public function __construct() {
        $this->conn = ConnessioneDB::getInstance()->getConnessione();
    }

    // Metodo per ottenere tutti i commenti di una specifica escursione
    public function getCommenti($escursione_id) {
        $sql = "SELECT c.id, c.user_id, c.commento, c.mi_piace, c.non_mi_piace, u.nome 
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
    public function aggiungiMiPiace($commento_id, $user_id, $escursione_id) {
        if ($this->registraVoto($commento_id, $user_id, 'mi_piace')) {
            return $this->incrementaMiPiace($commento_id);
        }
        return false;
    }

    // Metodo per aggiungere un "Non Mi Piace" a un commento
    public function aggiungiNonMiPiace($commento_id, $user_id, $escursione_id) {
        if ($this->registraVoto($commento_id, $user_id, 'non_mi_piace')) {
            return $this->incrementaNonMiPiace($commento_id);
        }
        return false;
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

    // Metodo per registrare un voto (Mi Piace / Non Mi Piace)
    public function registraVoto($commento_id, $user_id, $voto) {
        $sql = "SELECT id FROM voti WHERE commento_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $commento_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Se esiste già un voto dell'utente, lo aggiorniamo
            $stmt->close();
            $sql = "UPDATE voti SET voto = ? WHERE commento_id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sii', $voto, $commento_id, $user_id);
            $esito = $stmt->execute();
        } else {
            // Se non esiste, inseriamo il nuovo voto
            $stmt->close();
            $sql = "INSERT INTO voti (commento_id, user_id, voto) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('iis', $commento_id, $user_id, $voto);
            $esito = $stmt->execute();
        }

        $stmt->close();
        return $esito;
    }
}
?>
