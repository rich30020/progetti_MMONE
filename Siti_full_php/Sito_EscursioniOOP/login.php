<?php
session_start();
include 'connessione.php';

if (!isset($db->conn)) {
    die("Database connection not established.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $eta = $_POST['eta'];
    $esperienza = $_POST['livello_esperienza'];

    $stmt = $db->conn->prepare("SELECT id, nome FROM utenti WHERE nome = ? AND eta = ? AND livello_esperienza = ?");
    $stmt->bind_param("sis", $nome, $eta, $esperienza); 

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id']; // Salviamo l'ID utente nella sessione
        $_SESSION['nome'] = $user['nome']; 
        header("Location: dashboard.php");
        exit();
    } else {
        // Messaggio di errore generico
        echo "Credenziali errate!";
    }

    $stmt->close();
}

$db->conn->close();
?>
