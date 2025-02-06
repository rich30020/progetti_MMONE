<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];

    // Connessione al database
    include 'connessione.php';

    $sql = "SELECT * FROM utenti WHERE nome='$nome'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['nome'] = $nome;
        header("Location: dashboard.php"); // Reindirizza alla pagina dashboard.php
        exit();
    } else {
        echo "Nome utente non trovato.";
    }

    $conn->close();
}
?>
