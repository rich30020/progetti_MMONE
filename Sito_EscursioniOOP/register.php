<?php
include 'connessione.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // Verifica se il nome utente esiste già
    $sql = "SELECT * FROM utenti WHERE nome='$nome'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Nome utente già esistente.";
    } else {
        // Inserisce il nuovo utente nel database
        $sql = "INSERT INTO utenti (nome, password, email) VALUES ('$nome', '$password', '$email')";
        if ($conn->query($sql) === TRUE) {
            echo "Registrazione riuscita. <a href='login.html'>Accedi</a>";
        } else {
            echo "Errore nella registrazione: " . $conn->error;
        }
    }
}

$conn->close();
?>
