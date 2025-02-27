<?php
require_once __DIR__ . '/../model/ConnessioneDB.php';

class Utente {
    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    // Ottieni un utente per ID
    public function getUserById($userId) {
        $query = "SELECT id, nome, email, eta, livello_esperienza FROM utenti WHERE id = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($row = $result->fetch_assoc()) {
                return $row;
            }

            return null; 
        }
        return null;
    }

    // Modifica il profilo di un utente
    public function updateProfile($userId, $nome, $email, $eta, $livello_esperienza) {
        $query = "UPDATE utenti SET nome = ?, email = ?, eta = ?, livello_esperienza = ? WHERE id = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("ssisi", $nome, $email, $eta, $livello_esperienza, $userId);

            if ($stmt->execute()) {
                return true; 
            }

            return false;
        }
        return false;
    }

    // Verifica se l'email esiste già nel database
    public function emailEsistente($email) {
        $query = "SELECT id FROM utenti WHERE email = ?";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                return true;
            }

            return false; 
        }
        return false;
    }

    // Crea un nuovo utente nel database
    public function creaUtente($nome, $email, $password, $eta, $livello_esperienza) {
        $query = "INSERT INTO utenti (nome, email, password, eta, livello_esperienza) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $this->db->prepare($query)) {
            $stmt->bind_param("ssssi", $nome, $email, $password, $eta, $livello_esperienza);

            if ($stmt->execute()) {
                return true; 
            }

            return false; 
        }
        return false; 
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
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Errore nel controllo del voto: " . $e->getMessage());
            return false;
        }
    }

    // Registra il voto dell'utente per una determinata escursione
    public function registraVoto($userId, $escursioneId, $voto) {
        try {
            if ($this->haVotato($userId, $escursioneId)) {
                return false; 
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
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nella verifica delle credenziali: " . $e->getMessage());
            return null;
        }
    }
    

}
?>
