<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Voto {

    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite mysqli
        $this->db = ConnessioneDB::getInstance()->getConnessione(); // Ensure proper retrieval of the connection instance
    }

    // Aggiungi un voto (Mi Piace o Non Mi Piace)
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        $query = "INSERT INTO voti (user_id, commento_id, voto, escursione_id) VALUES (?, ?, ?, ?)";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa i parametri
            $stmt->bind_param("iiii", $user_id, $commento_id, $voto, $escursione_id);

            // Esegui la query e verifica se è andata a buon fine
            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Errore nell'esecuzione della query: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }
    }

    public function aggiornaVoto($user_id, $commento_id, $voto) {
        $query = "UPDATE voti SET voto = ? WHERE user_id = ? AND commento_id = ?";

        if ($stmt = $this->db->prepare($query)) {

            $stmt->bind_param("iii", $voto, $user_id, $commento_id);

            if ($stmt->execute()) {
                return true;
            } else {
                error_log("Errore nell'esecuzione della query: " . $stmt->error);
                return false;
            }
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }
    }

    public function getVotiPerCommento($commento_id) {
        $query = "SELECT * FROM voti WHERE commento_id = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $commento_id);

            $stmt->execute();

            $result = $stmt->get_result();

            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }
    }

    public function getLikeDislikeCount($commento_id, $voto) {
        $query = "SELECT COUNT(*) as count FROM voti WHERE commento_id = ? AND voto = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("ii", $commento_id, $voto);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'];
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return 0;
        }
    }

    public function getVotoPerCommentoUtente($user_id, $commento_id) {
        $query = "SELECT voto FROM voti WHERE user_id = ? AND commento_id = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("ii", $user_id, $commento_id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                return $row['voto'];
            }

            return null;
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return null;
        }
    }
}
?>
