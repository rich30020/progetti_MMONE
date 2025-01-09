<?php
session_start(); // Avvia la sessione

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html"); // Reindirizza se non loggato
  exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname_kitchen = "kitchen";

// Connessione al database
$conn_kitchen = new mysqli($servername, $username, $password, $dbname_kitchen);

if ($conn_kitchen->connect_error) {
  die("Connessione fallita: " . $conn_kitchen->connect_error); // Mostra errore connessione
}

$sql = "SELECT id FROM ricette ORDER BY RAND() LIMIT 1"; // Seleziona un ID ricetta casuale
$result = $conn_kitchen->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $randomRecipeId = $row['id'];
  header("Location: ricetta.php?book_id=" . $randomRecipeId); // Reindirizza alla pagina della ricetta casuale
  exit;
} else {
  die("Errore: Nessuna ricetta trovata."); // Mostra errore se non ci sono ricette
}

$conn_kitchen->close(); // Chiude la connessione al database

?>
