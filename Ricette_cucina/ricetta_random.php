<?php
session_start(); 

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html"); // Reindirizza se non loggato
  exit;
}

include 'connessione_ricette.php';

// Carica tutte le ricette
$sql = "SELECT id FROM ricette ORDER BY RAND() LIMIT 1"; // Seleziona un ID ricetta casuale
$result = $conn_kitchen->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $randomRecipeId = $row['id'];
  header("Location: ricetta.php?book_id=" . $randomRecipeId); // Va alla pagina della ricetta casuale
  exit;
} else {
  die("Errore: Nessuna ricetta trovata."); 
}

$conn_kitchen->close(); // Chiude la connessione al database
?>
