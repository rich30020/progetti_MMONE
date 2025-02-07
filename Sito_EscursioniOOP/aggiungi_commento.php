<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

include 'connessione.php';

class Commento {
    private $conn;
    private $escursione_id;
    private $commento;
    private $user_id;

    public function __construct($conn, $escursione_id, $commento, $user_id) {
        $this->conn = $conn;
        $this->escursione_id = $escursione_id;
        $this->commento = $commento;
        $this->user_id = $user_id;
    }

    public function aggiungi() {
        $sql = "INSERT INTO commenti (escursione_id, user_id, commento) 
                VALUES ('$this->escursione_id', '$this->user_id', '$this->commento')";
        return $this->conn->query($sql);
    }

    public function getUserId($nome) {
        $sql = "SELECT id FROM utenti WHERE nome='$nome'";
        $result = $this->conn->query($sql);
        $utente = $result->fetch_assoc();
        return $utente['id'];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escursione_id = $_POST['escursione_id'];
    $commento = $_POST['commento'];
    $user_id = (new Commento($conn, null, null, null))->getUserId($_SESSION['nome']);

    $commentoObj = new Commento($conn, $escursione_id, $commento, $user_id);

    if ($commentoObj->aggiungi()) {
        header("Location: esplora.php");
        exit();
    } else {
        echo "Errore: " . $conn->error;
    }
}

$conn->close();
?>
