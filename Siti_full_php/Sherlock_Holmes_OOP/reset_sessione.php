<?php
// Classe per gestire le operazioni relative ai giocatori
class Giocatore {
    private $conn;
    private $giocatoreId;

    public function __construct($conn, $giocatoreId) {
        $this->conn = $conn;
        $this->giocatoreId = $giocatoreId;
    }

    // Metodo per resettare i punti del giocatore
    public function resetPunti() {
        $stmt = $this->conn->prepare("UPDATE giocatori SET punti = 0 WHERE giocatore_id = ?");
        $stmt->bind_param("i", $this->giocatoreId);
        $stmt->execute();
        $stmt->close();
    }
}

session_start();

$giocatoreId = 1;
include 'connessione.php';

// Creazione dell'oggetto Giocatore
$giocatore = new Giocatore($conn, $giocatoreId);

$giocatore->resetPunti();

session_destroy();


header('Location: index.php');
exit();
?>
