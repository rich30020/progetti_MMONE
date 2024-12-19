<?php
// Connessione al database
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "sistema_login";

$connessione = new mysqli($host, $user, $password, $database);

if ($connessione->connect_error) {
    die("Errore di connessione: " . $connessione->connect_error);
}

// Verifica se il metodo di richiesta è POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    // Hash della password
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Prepara l'inserimento dei dati nella tabella utenti
    $sql = "INSERT INTO utenti (email, username, password) VALUES (?, ?, ?)";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("sss", $email, $username, $password);

    if ($stmt->execute()) {
        echo "Registrazione avvenuta con successo. <a href='login.html'>Accedi</a>";
    } else {
        echo "Errore nella registrazione: " . $stmt->error;
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $connessione->close();
}
?>
