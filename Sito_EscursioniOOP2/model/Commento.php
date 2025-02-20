<?php

require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Commenti {
    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance()->getConnessione();
    }

    public function getCommentiByEscursione($escursione_id) {
        $query = "SELECT * FROM commenti WHERE escursione_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $escursione_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $commenti = [];

        while ($row = $result->fetch_assoc()) {
            $commenti[] = $row;
        }

        $stmt->close();
        return $commenti;
    }

    public function incrementaMiPiace($commento_id) {
        $query = "UPDATE commenti SET mi_piace = mi_piace + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $commento_id);
        $stmt->execute();
        $stmt->close();
    }

    public function incrementaNonMiPiace($commento_id) {
        $query = "UPDATE commenti SET non_mi_piace = non_mi_piace + 1 WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $commento_id);
        $stmt->execute();
        $stmt->close();
    }
}

?>
