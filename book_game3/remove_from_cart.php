<?php
session_start();

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html");
  exit;
}

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

// Rimozione del libro dal carrello
if (isset($_POST['remove_from_cart']) && isset($_POST['book_id'])) {
  $userId = $_SESSION['user_id'];
  $bookId = $_POST['book_id'];

  $stmt = $conn->prepare("DELETE FROM cart WHERE utenti_id = ? AND book_id = ?");
  $stmt->bind_param("ii", $userId, $bookId);

  if ($stmt->execute()) {
    echo "<p>Il libro è stato rimosso dal carrello!</p>";
  } else {
    echo "<p>Errore durante la rimozione dal carrello.</p>";
  }

  $stmt->close();
}

$conn->close();
header("Location: cart.php");
exit();
?>
