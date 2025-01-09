<?php
session_start();

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html");
  exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname_kitchen = "kitchen";

$conn_kitchen = new mysqli($servername, $username, $password, $dbname_kitchen);

if ($conn_kitchen->connect_error) {
  die("Connessione fallita: " . $conn_kitchen->connect_error);
}

if (isset($_GET['book_id'])) {
  $bookId = $_GET['book_id'];

  $sql = "SELECT * FROM ricette WHERE id = ?";
  $stmt = $conn_kitchen->prepare($sql);
  $stmt->bind_param("i", $bookId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $ricetta = $result->fetch_assoc();
  } else {
    die("Ricetta non trovata.");
  }
  $stmt->close();
} else {
  die("Errore: ID ricetta non fornito.");
}

$conn_kitchen->close();
?>

<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css">
  <link rel="icon" type="image/x-icon" href="images/book.png">
  <title><?= $ricetta['nome'] ?></title>
  <style>
    .responsive-img {
      width: 100%;
      height: auto;
      max-width: 100%;
      display: block;
      margin: 0 auto;
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
    }

    .back-button:hover {
      background-color: #f7b731;
    }
  </style>
</head>

<body>
  <h1><?= $ricetta['nome'] ?></h1>
  <img class="responsive-img" src="<?= $ricetta['image_url'] ?>" alt="<?= $ricetta['nome'] ?>">
  <p><?= $ricetta['descrizione'] ?></p>
  <h3>Ingredienti:</h3>
  <p><?= $ricetta['ingredienti'] ?></p>
  <h3>Tempo di preparazione:</h3>
  <p><?= $ricetta['tempo_di_preparazione'] ?> minuti</p>
  <h3>Grado di difficoltà:</h3>
  <p><?= $ricetta['grado_di_difficolta'] ?></p>

  <a href="area-privata.php" class="back-button">Torna alla pagina principale</a>
</body>

</html>
