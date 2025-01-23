<?php
// Parametri di connessione al database
$host = 'localhost';
$dbname = 'gioco_pirati';
$username = 'root';
$password = '';

// Crea una connessione mysqli al database
$conn = new mysqli($host, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
?>
