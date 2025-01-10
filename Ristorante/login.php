<?php
session_start();

// Connessione al database
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "Ristorante";

$connessione = new mysqli($host, $user, $password, $database);

if ($connessione->connect_error) {
    die("Errore di connessione: " . $connessione->connect_error);
}

// Verifica se il metodo di richiesta è POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Seleziona l'utente dal database
    $sql = "SELECT * FROM utenti WHERE email = ?";
    $stmt = $connessione->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Verifica la password
        if (password_verify($password, $user['password'])) {
            // Salva l'ID utente e l'email nella sessione e reindirizza all'area privata
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome']; // Salva il nome dell'utente nella sessione
            $_SESSION['email'] = $email;
            $_SESSION['loggato'] = true;
            header("Location: area-privata.php");
            exit();
        } else {
            echo "Password errata.";
        }
    } else {
        echo "Email non trovata.";
    }

    // Chiudi lo statement e la connessione
    $stmt->close();
    $connessione->close();
    echo "Finito";
}
?>
