<?php
session_start();

// Connessione al database
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "kitchen";

$connessione = new mysqli($host, $user, $password, $database);

if ($connessione->connect_error) {
    die("Errore di connessione: " . $connessione->connect_error);
}

// Verifica se il metodo di richiesta è POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Seleziona l'utente dal database
    $sql = "SELECT * FROM utenti WHERE username = ?";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica la password
        if (password_verify($password, $user['password'])) {
            // Salva l'ID utente e l'username nella sessione e reindirizza all'area privata
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            $_SESSION['loggato'] = true;
            header("Location: area-privata.php");
            exit();
        } else {
            echo "Password errata.";
        }
    } else {
        echo "Username non trovato.";
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $connessione->close();
    echo "Finito";
}
?>
