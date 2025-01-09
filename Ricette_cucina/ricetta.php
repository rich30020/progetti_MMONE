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

$ricetta = null;

if (isset($_GET['book_id'])) {
  $bookId = $_GET['book_id'];

  $sql = "SELECT * FROM ricette WHERE id = ?"; // Query con parametro
  $stmt = $conn_kitchen->prepare($sql);
  $stmt->bind_param("i", $bookId); // Associa parametro
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $ricetta = $result->fetch_assoc(); // Ottiene dati della ricetta
  } else {
    die("Ricetta non trovata."); // Mostra errore se la ricetta non esiste
  }
  $stmt->close();
} else {
  die("Errore: ID ricetta non fornito."); // Mostra errore se l'ID non è fornito
}

$conn_kitchen->close(); // Chiude la connessione al database
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css">
  <link rel="icon" type="image/x-icon" href="images/book.png">
  <title><?= isset($ricetta) ? htmlspecialchars($ricetta['nome']) : 'Ricetta' ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      line-height: 1.6;
      background-color: #f9f9f9;
    }

    h1 {
      color: #333;
      font-weight: bold;
      text-align: center;
      margin-top: 20px;
    }

    h3 {
      color: #555;
      font-weight: bold;
      margin-top: 20px;
    }

    p {
      margin: 15px 0;
      text-align: justify;
      font-size: 1.1em;
      color: #666;
    }

    .responsive-img {
      width: 100%;
      height: auto;
      max-width: 800px;
      display: block;
      margin: 20px auto;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .back-button {
      display: block;
      width: 200px;
      margin: 20px auto;
      padding: 10px;
      background-color: #f7b731;
      color: white;
      text-align: center;
      text-decoration: none;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }

    .back-button:hover {
      background-color: #e0a300;
    }
  </style>
</head>

<body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <?php include 'navbar.php'?>

  <div class="container">
    <?php if ($ricetta): ?>
      <h1><?= htmlspecialchars($ricetta['nome']) ?></h1>
      <img class="responsive-img" src="<?= htmlspecialchars($ricetta['image_url']) ?>" alt="<?= htmlspecialchars($ricetta['nome']) ?>">
      <p><?= htmlspecialchars($ricetta['descrizione']) ?></p>
      
      <h3>Ingredienti:</h3>
      <p><?= nl2br(htmlspecialchars($ricetta['ingredienti'])) ?></p>
      
      <h3>Tempo di preparazione:</h3>
      <p><?= htmlspecialchars($ricetta['tempo_di_preparazione']) ?> minuti</p>
      
      <h3>Grado di difficoltà:</h3>
      <p><?= htmlspecialchars($ricetta['grado_di_difficolta']) ?></p>
    <?php else: ?>
      <p>Errore: Ricetta non trovata.</p>
    <?php endif; ?>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
