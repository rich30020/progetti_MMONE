<?php
require_once __DIR__ . '/../model/ConnessioneDB.php';

class Commenti {
    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    // Metodo per aggiungere "mi piace" a un commento
    public function aggiungiMiPiace($commento_id, $user_id, $escursione_id) {
        try {
            $query = "INSERT INTO mi_piace (commento_id, user_id, escursione_id) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("iii", $commento_id, $user_id, $escursione_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Errore nell'aggiunta del mi piace: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per aggiungere "non mi piace" a un commento
    public function aggiungiNonMiPiace($commento_id, $user_id, $escursione_id) {
        try {
            $query = "INSERT INTO non_mi_piace (commento_id, user_id, escursione_id) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("iii", $commento_id, $user_id, $escursione_id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Errore nell'aggiunta del non mi piace: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per ottenere un commento per ID
    public function getCommentoById($commento_id) {
        try {
            $query = "SELECT commenti.*, 
                             (SELECT COUNT(*) FROM mi_piace WHERE commento_id = commenti.id) as like_count,
                             (SELECT COUNT(*) FROM non_mi_piace WHERE commento_id = commenti.id) as dislike_count
                      FROM commenti 
                      WHERE commenti.id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("i", $commento_id);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Errore nel recupero del commento: " . $e->getMessage());
            return null;
        }
    }

    // Metodo per aggiungere un commento
    public function aggiungiCommento($escursione_id, $user_id, $commento) {
        try {
            $query = "INSERT INTO commenti (escursione_id, user_id, commento, data_creazione) VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param('iis', $escursione_id, $user_id, $commento);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Errore nell'aggiunta del commento: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per ottenere i commenti di un'escursione
    public function getCommenti($escursione_id) {
        try {
            $query = "SELECT * FROM commenti WHERE escursione_id = ?";
            $stmt = $this->db->prepare($query);
            if ($stmt === false) {
                throw new Exception("Errore nella preparazione della query: " . $this->db->error);
            }
            $stmt->bind_param("i", $escursione_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $commenti = [];
            while ($row = $result->fetch_assoc()) {
                $commenti[] = $row;
            }
            return $commenti;
        } catch (Exception $e) {
            error_log("Errore nel recupero dei commenti: " . $e->getMessage());
            return [];
        }
    }
}
