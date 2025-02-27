<?php

require_once __DIR__ . '/ConnessioneDB.php';

class Escursione {

    private $conn;

    public function __construct() {
        $this->conn = ConnessioneDB::getInstance()->getConnessione();
    }

    public function getAllEscursioni() {
        $query = "SELECT * FROM escursioni";
        $result = $this->conn->query($query);

        if ($result === false) {
            error_log("Errore nella query: " . $this->conn->error);
            return [];
        }

        if ($result->num_rows > 0) {
            $escursioni = [];
            while ($row = $result->fetch_assoc()) {
                $escursioni[] = $row;
            }
            return $escursioni;
        } else {
            return [];
        }
    }

    public function getEscursioneById($id) {
        $query = "SELECT * FROM escursioni WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->conn->error);
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    public function insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        $query = "INSERT INTO escursioni (user_id, sentiero, durata, difficolta, punti, foto) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("isiiis", $userId, $sentiero, $durata, $difficolta, $punti, $foto);
        return $stmt->execute();
    }
}
?>
