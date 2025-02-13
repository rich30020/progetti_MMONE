<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php';

class Escursione {

    private $db;

    public function __construct() {
        $this->db = ConnessioneDB::getInstance(); // Ottieni la connessione dal Singleton
    }

    // Funzione per recuperare tutte le escursioni
    public function getAllEscursioni() {
        $query = "SELECT * FROM escursioni";
        $stmt = $this->db->query($query);  // Usa PDO per eseguire la query

        $escursioni = $stmt->fetchAll(PDO::FETCH_ASSOC);  // Ottieni tutti i risultati
        return $escursioni;
    }

    // Funzione per recuperare un'escursione per ID
    public function getEscursioneById($id) {
        $query = "SELECT * FROM escursioni WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Funzione per inserire una nuova escursione
    public function insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        $query = "INSERT INTO escursioni (user_id, sentiero, durata, difficolta, punti, foto) 
                  VALUES (:user_id, :sentiero, :durata, :difficolta, :punti, :foto)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':sentiero', $sentiero, PDO::PARAM_STR);
        $stmt->bindParam(':durata', $durata, PDO::PARAM_INT);
        $stmt->bindParam(':difficolta', $difficolta, PDO::PARAM_STR);
        $stmt->bindParam(':punti', $punti, PDO::PARAM_INT);
        $stmt->bindParam(':foto', $foto, PDO::PARAM_STR);

        return $stmt->execute();
    }
}
?>
