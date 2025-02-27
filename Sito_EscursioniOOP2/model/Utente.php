<?php
require_once __DIR__ . '/../model/ConnessioneDB.php';

class Utente {
    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    // Verifica se un utente ha già votato per una determinata escursione
    public function haVotato($userId, $escursioneId) {
        try {
            $query = "SELECT COUNT(*) AS count FROM voti WHERE user_id = ? AND escursione_id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("ii", $userId, $escursioneId);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            return $row['count'] > 0;  // Restituisce true se ha già votato
        } catch (Exception $e) {
            error_log("Errore nel controllo del voto: " . $e->getMessage());
            return false;
        }
    }

    // Registra il voto dell'utente per una determinata escursione
    public function registraVoto($userId, $escursioneId, $voto) {
        try {
            // Prima controlliamo se l'utente ha già votato
            if ($this->haVotato($userId, $escursioneId)) {
                return false;  // Se l'utente ha già votato, non possiamo registrare il voto
            }

            // Inseriamo il voto nella tabella voti
            $query = "INSERT INTO voti (user_id, escursione_id, voto) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("iii", $userId, $escursioneId, $voto);
            return $stmt->execute();  // Restituisce true se il voto è stato registrato
        } catch (Exception $e) {
            error_log("Errore nel salvataggio del voto: " . $e->getMessage());
            return false;
        }
    }

    public function verificaCredenziali($email) {
        try {
            $query = "SELECT * FROM utenti WHERE email = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc(); // Restituisce l'array con i dati dell'utente o NULL se non trovato
        } catch (Exception $e) {
            error_log("Errore nella verifica delle credenziali: " . $e->getMessage());
            return null;
        }
    }
    

}
?>
