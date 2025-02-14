<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Voto {

    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite mysqli
        $this->db = ConnessioneDB::getInstance();
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

    // Aggiorna un voto esistente
    public function aggiornaVoto($user_id, $commento_id, $voto) {
        $query = "UPDATE voti SET voto = ? WHERE user_id = ? AND commento_id = ?";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa i parametri
            $stmt->bind_param("iii", $voto, $user_id, $commento_id);

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

    // Recupera i voti per un commento
    public function getVotiPerCommento($commento_id) {
        $query = "SELECT * FROM voti WHERE commento_id = ?";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa il parametro
            $stmt->bind_param("i", $commento_id);

            // Esegui la query
            $stmt->execute();

            // Ottieni i risultati
            $result = $stmt->get_result();

            // Restituisci i risultati come array associativo
            return $result->fetch_all(MYSQLI_ASSOC);
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return false;
        }
    }

    // Ottieni il conteggio dei Mi Piace e Non Mi Piace per un'escursione
    public function getLikeDislikeCount($escursione_id, $voto) {
        $query = "SELECT COUNT(*) as count FROM voti WHERE escursione_id = ? AND voto = ?";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa i parametri
            $stmt->bind_param("ii", $escursione_id, $voto);

            // Esegui la query
            $stmt->execute();

            // Ottieni i risultati
            $result = $stmt->get_result();

            // Restituisci il conteggio dei Mi Piace o Non Mi Piace
            $row = $result->fetch_assoc();
            return $row['count'];
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return 0;
        }
    }

    // Verifica se un utente ha già votato un commento
    public function getVotoPerCommentoUtente($user_id, $commento_id) {
        $query = "SELECT voto FROM voti WHERE user_id = ? AND commento_id = ?";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa i parametri
            $stmt->bind_param("ii", $user_id, $commento_id);

            // Esegui la query
            $stmt->execute();

            // Ottieni i risultati
            $result = $stmt->get_result();

            // Se l'utente ha votato, restituisce il voto
            if ($row = $result->fetch_assoc()) {
                return $row['voto'];
            }

            // Se non ha votato, restituisce null
            return null;
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return null;
        }
    }
}
?>
