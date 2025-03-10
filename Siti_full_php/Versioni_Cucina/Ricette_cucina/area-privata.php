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

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
} else {
  die("Errore: ID utente non trovato. Assicurati di essere loggato.");
}

// Carica le ricette dal database all'array $library
$library = [];

$sql = "SELECT * FROM ricette";
$result = $conn_kitchen->query($sql);

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

// Inizializza l'array $randomRecipes
$randomRecipes = [];

// Seleziona 3 ricette casuali se ci sono abbastanza ricette, altrimenti usa tutte le ricette disponibili
if (count($library) > 3) {
  $randomKeys = array_rand($library, 3);
  foreach ($randomKeys as $key) {
    $randomRecipes[] = $library[$key];
  }
} else {
  $randomRecipes = $library;
}

if (isset($_POST['reset'])) {
  // Resetta le categorie selezionate e ricarica la pagina
  unset($_SESSION['selected_genres']);
  header("Location: area-privata.php");
  exit;
}

// Carica le immagini delle ricette per il carousel
$sql = "SELECT image_url FROM ricette";
$result = $conn_kitchen->query($sql);

$imageUrls = [];
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $imageUrls[] = $row['image_url'];
  }
}

$conn_kitchen->close(); // Chiude la connessione al database
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
    /* Corpo della pagina */
    body {
      background-color: #f5f5f5;
      font-family: "Georgia", serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    /* Contenitore generale */
    .container {
      display: flex;
      flex-direction: column;
      height: 30vh;
    }

    /* Navbar */
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

    .dropdown-menu-custom {
      background-color: #4CAF50;
    }

    .dropdown-menu-custom .dropdown-item {
      color: #fff;
    }

    .dropdown-menu-custom .dropdown-item:hover {
      background-color: #FFD700;
      color: #000;
    }

    /* Spazio principale della pagina */
    .main {
      flex: 1;
      padding: 20px;
      background: #ecf0f1;
      margin-top: 80px;
      /* Aggiungi spazio per la navbar */
    }

    /* Container della libreria */
    .library-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin-top: 20px;
    }

    /* Card dei libri */
    .book {
      margin: 10px;
      padding: 10px;
      background-color: #fff;
      border: 1px solid #ddd;
      border-radius: 5px;
      width: 200px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      box-sizing: border-box;
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

    /* Pulsante per reset della libreria */
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

    /* Titoli */
    h1,
    h2,
    p {
      text-align: center;
      color: #426384;
      font-family: "Georgia", serif;
    }

    h2 {
      margin-top: 40px;
      width: 100%;
    }

    /* Immagini dei libri */
    .book-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    /* Cart e logout */
    .cart-icon,
    .logout {
      position: fixed;
      top: 10px;
      z-index: 100;
    }

    .cart-icon {
      right: 30px;
      width: 30px;
      height: 30px;
    }

    .logout {
      right: 70px;
      height: 30px;
    }

    /* Carousel */
    .carousel {
      display: flex;
      overflow: hidden;
      position: relative;
      width: 100%;
    }

    .carousel-wrapper {
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

    /* Responsività */
    @media (max-width: 768px) {
      .navbar {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px;
      }

      .navbar button,
      .navbar form {
        margin: 5px 0;
      }

      .book {
        width: 90%;
      }

      .carousel img {
        height: 200px;
      }

      .carousel-button {
        padding: 8px;
        font-size: 18px;
      }

      .logout {
        right: 20px;
        top: 10px;
      }
    }

    .navbar-nav .nav-link:hover {
      color: #ffcc00;
      background-color: #343a40;
      border-radius: 5px;
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
  </style>
</head>

<body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <?php include 'navbar.php'?>

  <!-- Carousel -->
  <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
      <?php foreach ($imageUrls as $index => $imageUrl): ?>
        <li data-target="#carouselExampleIndicators" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
      <?php endforeach; ?>
    </ol>
    <div class="carousel-inner">
      <?php foreach ($imageUrls as $index => $imageUrl): ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <img class="d-block w-100" src="<?= $imageUrl ?>" alt="Slide <?= $index + 1 ?>">
        </div>
      <?php endforeach; ?>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
  </div>

  <div class="main">
    <h1>Le Nostre Ricette</h1>
    <p>Ciao <?php echo $_SESSION["username"]; ?>, seleziona una Ricetta!</p>
    <div class="library-container">
      <?php foreach ($library as $ricetta): ?>
        <div class="book">
          <form method="get" action="ricetta.php">
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

  <!-- Bottone per aggiornare le card -->
  <div class="text-center my-4">
    <button class="btn btn-warning" onclick="window.location.reload();">Nuove Ricette</button>
  </div>

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
                frameBorder="0"   
              ></iframe></p>
            </div>
          </div>
        </div>
      </section>
    </div>
  </footer>   

  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>

</html>

