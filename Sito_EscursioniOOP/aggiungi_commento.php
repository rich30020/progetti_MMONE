<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escursione_id = $_POST['escursione_id'];
    $commento = $_POST['commento'];
    $utente_nome = $_SESSION['nome'];

    // includo la connessione al db
    include 'connessione.php';

    // ottieni l'utente id
    $sql = "SELECT id FROM utenti WHERE nome='$utente_nome'";
    $result = $conn->query($sql);
    $utente = $result->fetch_assoc();
    $user_id = $utente['id'];

    // inserisci il commento
    $sql = "INSERT INTO commenti (escursione_id, user_id, commento) VALUES ('$escursione_id', '$user_id', '$commento')";
    if ($conn->query($sql) === TRUE) {
        header("Location: esplora.php");
        exit();
    } else {
        echo "Errore: " . $conn->error;
    }

    $conn->close();
}
?>
