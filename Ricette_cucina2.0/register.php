<?php
session_start();

include 'connessione_utenti.php'; // Include la connessione al database

// Verifica se il metodo di richiesta è POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    // Hash della password
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Prepara l'inserimento dei dati nella tabella utenti
    $sql = "INSERT INTO utenti (email, username, password) VALUES (?, ?, ?)";
    $stmt = $conn_kitchen->prepare($sql);
    $stmt->bind_param("sss", $email, $username, $password);

    if ($stmt->execute()) {
        echo "Registrazione avvenuta con successo. <a href='login.html'>Accedi</a>";
    } else {
        echo "Errore nella registrazione: " . $stmt->error;
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $conn_kitchen->close();
}
?>
