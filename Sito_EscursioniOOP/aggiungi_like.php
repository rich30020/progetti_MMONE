<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

include 'connessione.php';

class Voto {
    private $conn;
    private $escursione_id;

    public function __construct($conn, $escursione_id) {
        $this->conn = $conn;
        $this->escursione_id = $escursione_id;
    }

    public function aggiungiLike() {
        $sql = "SELECT non_mi_piace FROM escursioni WHERE id=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->escursione_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['non_mi_piace'] > 0) {
            $sql = "UPDATE escursioni SET non_mi_piace = non_mi_piace - 1, mi_piace = mi_piace + 1 WHERE id = ?";
        } else {
            $sql = "UPDATE escursioni SET mi_piace = mi_piace + 1 WHERE id = ?";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->escursione_id);
        return $stmt->execute();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escursione_id = $_POST['escursione_id'];
    $voto = new Voto($db->conn, $escursione_id); // Use $db->conn to get the connection

    if ($voto->aggiungiLike()) {
        header("Location: esplora.php");
        exit();
    } else {
        echo "Errore: " . $db->conn->error;
    }
}

$db->conn->close();
