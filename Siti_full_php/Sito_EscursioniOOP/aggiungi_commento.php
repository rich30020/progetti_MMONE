<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

include 'connessione.php';

class Commento {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function aggiungi($escursione_id, $user_id, $commento) {
        $sql = "INSERT INTO commenti (escursione_id, user_id, commento, data) VALUES (?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iis", $escursione_id, $user_id, $commento);
        return $stmt->execute();
    }

    public function getUserId($nome) {
        $sql = "SELECT id FROM utenti WHERE nome = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $nome);
        $stmt->execute();
        $result = $stmt->get_result();
        $utente = $result->fetch_assoc();
        return $utente['id'] ?? null;
    }
}

$db = new ConnessioneDB();
$conn = $db->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escursione_id = $_POST['escursione_id'];
    $commento = $_POST['commento'];

    $commentoObj = new Commento($conn);
    $user_id = $commentoObj->getUserId($_SESSION['nome']);

    if ($user_id && $commentoObj->aggiungi($escursione_id, $user_id, $commento)) {
        header("Location: esplora.php"); // Torna alla pagina delle escursioni
        exit();
    } else {
        echo "Errore: " . $conn->error;
    }
}

$conn->close();
?>
