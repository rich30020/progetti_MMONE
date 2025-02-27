<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Voto {

    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    // Ottiene il voto di un utente per un commento
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
        }
        return null;
    }

    // Aggiungi un voto
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        // Verifica se l'utente ha già votato
        if ($this->getVotoPerCommentoUtente($user_id, $commento_id) !== null) {
            return ['success' => false, 'message' => 'Hai già votato questo commento.'];
        }
        
        // Inserisce il voto nel database
        $query = "INSERT INTO voti (user_id, commento_id, voto, escursione_id) VALUES (?, ?, ?, ?)";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("iiii", $user_id, $commento_id, $voto, $escursione_id);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Voto aggiunto con successo.'];
            }
            return ['success' => false, 'message' => 'Errore nell\'aggiunta del voto.'];
        }
        return ['success' => false, 'message' => 'Errore nella preparazione della query.'];
    }

    // Aggiorna un voto esistente
    public function aggiornaVoto($user_id, $commento_id, $voto) {
        $query = "UPDATE voti SET voto = ? WHERE user_id = ? AND commento_id = ?";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("iii", $voto, $user_id, $commento_id);
            if ($stmt->execute()) {
                return ['success' => true, 'message' => 'Voto aggiornato con successo.'];
            }
            return ['success' => false, 'message' => 'Errore nell\'aggiornamento del voto.'];
        }
        return ['success' => false, 'message' => 'Errore nella preparazione della query.'];
    }

    // Visualizza tutti i voti per un commento
    public function getVotiPerCommento($commento_id) {
        $query = "SELECT * FROM voti WHERE commento_id = ?";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $commento_id);
            $stmt->execute();
            return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        }
        return false;
    }

    // Conta il numero di like o dislike per un commento
    public function getLikeDislikeCount($commento_id, $voto) {
        $query = "SELECT COUNT(*) as count FROM voti WHERE commento_id = ? AND voto = ?";
        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("ii", $commento_id, $voto);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'];
        }
        return 0;
    }
}
?>
