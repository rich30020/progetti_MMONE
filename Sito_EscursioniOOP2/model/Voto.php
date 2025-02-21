<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Voto {

    private $db;

    public function __construct() {
        // Ottieni la connessione al database tramite mysqli
        $this->db = ConnessioneDB::getInstance()->getConnessione(); // Assicurati di ottenere correttamente l'istanza della connessione
    }

    // Metodo per ottenere il voto di un utente per un commento
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

    // Aggiungi un voto (Mi Piace o Non Mi Piace)
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        // Prima verifica se l'utente ha già votato per il commento
        if ($this->getVotoPerCommentoUtente($user_id, $commento_id) !== null) {
            // Se l'utente ha già votato, non permettere di inserire un altro voto
            return ['success' => false, 'message' => 'Hai già votato questo commento.'];
        }

        // Inserimento del voto nel database
        $query = "INSERT INTO voti (user_id, commento_id, voto, escursione_id) VALUES (?, ?, ?, ?)";

        // Prepara la query
        if ($stmt = $this->db->prepare($query)) {
            // Associa i parametri
            $stmt->bind_param("iiii", $user_id, $commento_id, $voto, $escursione_id);

            // Esegui la query e verifica se è andata a buon fine
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Voto aggiunto con successo.'];
            } else {
                error_log("Errore nell'esecuzione della query: " . $stmt->error);
                return ['success' => false, 'message' => 'Errore nell\'aggiunta del voto.'];
            }
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return ['success' => false, 'message' => 'Errore nella preparazione della query.'];
        }
    }

    // Aggiorna un voto esistente (ad esempio se l'utente cambia il suo voto)
    public function aggiornaVoto($user_id, $commento_id, $voto) {
        $query = "UPDATE voti SET voto = ? WHERE user_id = ? AND commento_id = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("iii", $voto, $user_id, $commento_id);

            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Voto aggiornato con successo.'];
            } else {
                error_log("Errore nell'esecuzione della query: " . $stmt->error);
                return ['success' => false, 'message' => 'Errore nell\'aggiornamento del voto.'];
            }
        } else {
            error_log("Errore nella preparazione della query: " . $this->db->error);
            return ['success' => false, 'message' => 'Errore nella preparazione della query.'];
        }
    }

    // Ottieni tutti i voti per un determinato commento
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

    // Conta il numero di "Mi Piace" o "Non Mi Piace" per un commento
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
}
?>
