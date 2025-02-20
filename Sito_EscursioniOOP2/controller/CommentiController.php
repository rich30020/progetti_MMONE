<?php
// Includiamo il file che gestisce la connessione al database
require_once __DIR__ . '/../model/ConnessioneDb.php';

class CommentiController {
    private $conn;

    public function __construct() {
        // Inizializza la connessione al database tramite il Singleton
        $this->conn = ConnessioneDB::getInstance()->getConnessione();
    }

    // Metodo per ottenere i commenti per una specifica escursione
    public function getCommenti($escursione_id) {
        $sql = "SELECT c.id, c.user_id, c.commento, c.mi_piace, c.non_mi_piace
                FROM commenti c
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
        // Sanitizza il commento per evitare iniezioni
        $commento = htmlspecialchars($commento);

        // Query per inserire il commento nel database
        $sql = "INSERT INTO commenti (escursione_id, user_id, commento, data) VALUES (?, ?, ?, NOW())";

        // Prepariamo la query
        $stmt = $this->conn->prepare($sql);
        
        // Bind dei parametri
        $stmt->bind_param('iis', $escursione_id, $user_id, $commento);

        // Eseguiamo la query
        if ($stmt->execute()) {
            $stmt->close();
            return true;  // Commento aggiunto con successo
        } else {
            $stmt->close();
            return false;  // Errore nell'aggiunta del commento
        }
    }

    // Metodo per incrementare il "Mi Piace"
    public function incrementaMiPiace($commento_id) {
        $sql = "UPDATE commenti SET mi_piace = mi_piace + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
        $stmt->close();
    }

    // Metodo per incrementare il "Non mi Piace"
    public function incrementaNonMiPiace($commento_id) {
        $sql = "UPDATE commenti SET non_mi_piace = non_mi_piace + 1 WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $commento_id);
        $stmt->execute();
        $stmt->close();
    }

    // Metodo per registrare un voto (Mi Piace / Non mi Piace)
    public function registraVoto($commento_id, $user_id, $voto) {
        // Verifica se l'utente ha già votato questo commento
        $sql = "SELECT * FROM voti WHERE commento_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $commento_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // L'utente ha già votato, quindi aggiorniamo il voto
            $sql = "UPDATE voti SET voto = ? WHERE commento_id = ? AND user_id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('sii', $voto, $commento_id, $user_id);
            $stmt->execute();
        } else {
            // L'utente non ha ancora votato, inseriamo un nuovo voto
            $sql = "INSERT INTO voti (commento_id, user_id, voto) VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param('iis', $commento_id, $user_id, $voto);
            $stmt->execute();
        }
        $stmt->close();
    }

    // Metodo per rimuovere un voto (Mi Piace / Non mi Piace)
    public function rimuoviVoto($commento_id, $user_id) {
        $sql = "DELETE FROM voti WHERE commento_id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ii', $commento_id, $user_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
