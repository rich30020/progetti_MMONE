<?php
session_start();
include 'connessione_ricette.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("location: login.html");
    exit;
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    die("Errore: ID utente non trovato. Assicurati di essere loggato.");
}

// Gestione della selezione della ricetta e salvataggio nella sessione
if (isset($_GET['select_book'])) {
  $ricettaId = $_GET['book_id'];

  // Salva l'ID della ricetta nella sessione
  $_SESSION['ricetta_selezionata'] = $ricettaId;

  // Aggiorna il conteggio dei clic per questa ricetta
  $updateQuery = "UPDATE ricette SET clicks = clicks + 1 WHERE id = ?";
  $stmt = $conn_kitchen->prepare($updateQuery);
  $stmt->bind_param('i', $ricettaId);
  $stmt->execute();
  $stmt->close();

  // Reindirizza l'utente alla pagina ricetta.php con l'ID della ricetta
  header("Location: ricetta.php?book_id=" . $ricettaId);
  exit();
}


// Carica tutte le ricette
$sql = "SELECT * FROM ricette";
$result = $conn_kitchen->query($sql);

$library = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $library[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'descrizione' => $row['descrizione'],
            'ingredienti' => $row['ingredienti'],
            'image_url' => $row['image_url'],
            'tempo_di_preparazione' => $row['tempo_di_preparazione'],
            'grado_di_difficolta' => $row['grado_di_difficolta']
        ];
    }
}

// Carica solo le ricette cliccate per il carousel
$carouselSql = "SELECT * FROM ricette WHERE clicks > 0";
$carouselResult = $conn_kitchen->query($carouselSql);

$randomRecipes = [];
if ($carouselResult->num_rows > 0) {
    while ($row = $carouselResult->fetch_assoc()) {
        $randomRecipes[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'descrizione' => $row['descrizione'],
            'image_url' => $row['image_url'],
            'tempo_di_preparazione' => $row['tempo_di_preparazione'],
            'grado_di_difficolta' => $row['grado_di_difficolta']
        ];
    }
}

$conn_kitchen->close();
?>





<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css" class="css">
  <link rel="icon" type="image/x-icon" href="./images/kitchen.png">
  <title>Gestione Ricette</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  
  <style>
    body {
      background-color: #f5f5f5;
      font-family: "Georgia", serif;
      margin-right: 0;
      padding: 0;
      box-sizing: border-box;
      overflow-x: hidden;
    }

    .navbar-custom {
      background-color: #4CAF50;
    }

    .navbar-custom .navbar-brand,
    .navbar-custom .navbar-nav .nav-link {
      color: #fff;
    }

    .navbar-custom .navbar-nav .nav-link:hover {
      color: #FFD700;
    }

    .main {
      flex: 1;
      padding: 20px;
      background: #ecf0f1;
      margin-top: 80px;
      box-sizing: border-box;
    }

    .library-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin-top: 20px;
    }

    .book {
      margin: 10px;
      padding: 10px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      width: 200px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      text-align: center;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .book:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .book button {
      background-color: #f7b731;
      color: white;
      border: none;
      padding: 5px 10px;
      cursor: pointer;
      border-radius: 5px;
      font-family: "Georgia", serif;
      font-style: italic;
      margin-top: 10px;
    }

    .book button:hover {
      background-color: #f7b731;
    }

    .reset-btn {
      background-color: #c0392b;
      color: white;
      border: none;
      padding: 10px 20px;
      cursor: pointer;
      border-radius: 5px;
      display: block;
      margin: 20px auto;
      font-family: "Georgia", serif;
    }

    .reset-btn:hover {
      background-color: #922b21;
    }

    h1, h2, p {
      text-align: center;
      color: #426384;
      font-family: "Georgia", serif;
    }

    h2 {
      margin-top: 40px;
    }

    .book-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .carousel {
      display: flex;
      overflow: hidden;
      position: relative;
      width: 100%;
      box-sizing: border-box;
    }

    .carousel-inner {
      display: flex;
      transition: transform 0.5s ease;
    }

    .carousel-item {
      flex: 0 0 100%;
      width: 100%;
      position: relative;
    }

    .carousel img {
      width: 100%;
      height: 710px;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .carousel img:hover {
      transform: scale(1.1);
    }

    .carousel-button {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.5);
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      border-radius: 50%;
      font-size: 20px;
      z-index: 10;
    }

    .carousel-button.left {
      left: 10px;
    }

    .carousel-button.right {
      right: 10px;
    }

    .navbar {
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .card-img-top {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .text-bold {
      font-weight: bold;
    }

    .library-container,
    .card-deck {
    width: 100%;
    box-sizing: border-box;
    }
  </style>
</head>

<body>
  <?php include 'navbar.php'?>

  <?php include 'carousel.php'?>

  <div class="main">
    <h1>Le Nostre Ricette</h1>
    <p>Ciao <?php echo $_SESSION["username"]; ?>, seleziona una Ricetta!</p>
    <div class="library-container">
      <?php foreach ($library as $ricetta): ?>
        <div class="book">
        <form method="get" action="area-privata.php">
          <img src="<?= $ricetta['image_url'] ?>" alt="<?= $ricetta['nome'] ?>" class="book-image">
          <span class="book-title"><?= $ricetta['nome']; ?></span>
          <span class="book-time"><span class="text-bold">Tempo di preparazione:</span> <?= $ricetta['tempo_di_preparazione']; ?> minuti</span>
          <span class="book-difficulty"><span class="text-bold">Grado di difficoltà:</span> <?= $ricetta['grado_di_difficolta']; ?></span>
          <input type="hidden" name="book_id" value="<?= $ricetta['id']; ?>">
          <button type="submit" name="select_book" class="btn btn-info-ricetta">Info Ricetta</button>
        </form>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <div class="card-deck">
    <?php foreach ($randomRecipes as $ricetta): ?>
      <div class="card">
        <img class="card-img-top" src="<?= $ricetta['image_url'] ?>" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><?= $ricetta['nome'] ?></h5>
          <p class="card-text"><?= $ricetta['descrizione'] ?></p>
          <p class="card-text"><small class="text-muted">Tempo di preparazione: <?= $ricetta['tempo_di_preparazione'] ?> minuti</small></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="text-center my-4">
    <button id="reload-cards-button" class="btn btn-warning">Nuove Ricette</button>
  </div>

  <script src="reload_card.js"></script>

  <footer class="text-center text-white" style="background-color: #f7b731;">
    <div class="container p-2">
      <section>
        <div class="row d-flex justify-content-center">
          <div class="col-12">
            <div class="ratio ratio-16x9">
              <p><iframe
                width="630" 
                height="350" 
                src="https://www.youtube.com/embed/_u_l3NeJBRU" 
                title="" 
                frameBorder="0"></iframe></p>
            </div>
          </div>
        </div>
      </section>
    </div>
  </footer>   


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

</body>

</html>