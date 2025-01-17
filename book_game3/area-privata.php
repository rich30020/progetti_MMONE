<?php

session_start();


if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html");
  exit;
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

if (isset($_SESSION['user_id'])) {
  $userId = $_SESSION['user_id'];
} else {
  die("Errore: ID utente non trovato. Assicurati di essere loggato.");
}


$library = [
  'historical_novel' => [],
  'adventure_action' => [],
  'fantasy' => [],
  'science_fiction' => [],
  'horror' => [],
];

// Carica i libri dal database all'array library
$sql = "SELECT * FROM books";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {

    $library[$row['genre']][] = [
      'NAME' => $row['NAME'],
      'image_url' => $row['image_url'],
      'prezzo' => $row['prezzo'] 
    ];
  }
}

// Gestisci le azioni di POST (selezione del libro o reset della libreria)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['select_genre']) && isset($_POST['genre'])) {
    // Seleziona il genere di libri scelto dall'utente
    $selectedGenre = $_POST['genre'];
    if (!isset($_SESSION['selected_genres'])) {
      $_SESSION['selected_genres'] = [];
    }

    if (!in_array($selectedGenre, $_SESSION['selected_genres'])) {
      $_SESSION['selected_genres'][] = $selectedGenre;
    }
  }

  if (isset($_POST['select_book']) && isset($_POST['book']) && isset($_POST['book_genre'])) {
    // Seleziona il libro scelto dall'utente
    $selectedBook = $_POST['book'];
    $bookGenre = $_POST['book_genre'];

    // Aggiungi il libro al carrello nel database
    $stmt = $conn->prepare("SELECT id, prezzo FROM books WHERE genre = ? AND name = ?");
    $stmt->bind_param("ss", $bookGenre, $selectedBook);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $bookId = $row['id'];
      $bookPrice = $row['prezzo'];

      // Inserisci il libro nel carrello dell'utente
      $stmt = $conn->prepare("INSERT INTO cart (utenti_id, book_id, prezzo) VALUES (?, ?, ?)");
      $stmt->bind_param("iid", $userId, $bookId, $bookPrice);
      $stmt->execute();
      $stmt->close();

      echo "<p>Il libro è stato aggiunto al carrello!</p>";
    }
  }

  if (isset($_POST['reset'])) {
    // Resetta le categorie selezionate e ricarica la pagina
    unset($_SESSION['selected_genres']);
    header("Location: area-privata.php");
    exit;
  }
}

// Chiudi la connessione al database
$conn->close();
?>



<!DOCTYPE html>
<html lang="it">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css" class="css">
  <link rel="icon" type="image/x-icon" href="images/book.png">
  <title>Gestione Libreria</title>
</head>

<body>
  <div class="container">
    <div class="sidebar">
      <h2>Tipologie di Libri</h2>

      <!-- Controlla se tutti i libri sono terminati -->
      <?php
      $allBooksFinished = count($library['historical_novel']) === 0 &&
        count($library['adventure_action']) === 0 &&
        count($library['fantasy']) === 0 &&
        count($library['science_fiction']) === 0 &&
        count($library['horror']) === 0;

      if ($allBooksFinished): ?>
        <p>Tutti i libri sono terminati. Per ricominciare, clicca sul pulsante qui sotto.</p>
        <!-- Form per ricomporre la libreria -->
        <form method="post" style="margin-top: 20px;">
          <button type="submit" name="reset" class="reset-btn">Ricomponi Libreria</button>
        </form>

      <?php else: ?>
        <!-- Mostra i pulsanti per selezionare i generi disponibili -->
        <?php if (count($library['historical_novel']) > 0): ?>
          <form method="post">
            <input type="hidden" name="genre" value="historical_novel">
            <button type="submit" name="select_genre">Romanzi Storici</button>
          </form>
        <?php endif; ?>
        <?php if (count($library['adventure_action']) > 0): ?>
          <form method="post">
            <input type="hidden" name="genre" value="adventure_action">
            <button type="submit" name="select_genre">Avventura e Azione</button>
          </form>
        <?php endif; ?>
        <?php if (count($library['fantasy']) > 0): ?>
          <form method="post">
            <input type="hidden" name="genre" value="fantasy">
            <button type="submit" name="select_genre">Fantasy</button>
          </form>
        <?php endif; ?>
        <?php if (count($library['science_fiction']) > 0): ?>
          <form method="post">
            <input type="hidden" name="genre" value="science_fiction">
            <button type="submit" name="select_genre">Fantascienza</button>
          </form>
        <?php endif; ?>
        <?php if (count($library['horror']) > 0): ?>
          <form method="post">
            <input type="hidden" name="genre" value="horror">
            <button type="submit" name="select_genre">Horror</button>
          </form>
        <?php endif; ?>
        <!-- Pulsante per ripristinare la libreria -->
        <form method="post" style="margin-top: 20px;">
          <button type="submit" name="reset" class="reset-btn">Ricomponi Libreria</button>
        </form>
      <?php endif; ?>
    </div>
    <div class="main">
      <h1>Gestione Libreria</h1>
      <!-- Link al carrello -->
      <a href="cart.php">
        <img
          src="./images/cart.png"
          alt="Carrello"
          class="cart-icon">
      </a>
      <!-- Pulsante di logout -->
      <form action="login.html" method="post">
        <button class='logout'>Esci</button>
      </form>

      <p>Ciao <?php echo $_SESSION["username"]; ?>, seleziona una categoria di libri!</p>
      <?php if (isset($_SESSION['selected_genres'])): ?>
        <div class="library-container">
          <?php foreach ($_SESSION['selected_genres'] as $selectedGenre): ?>
            <h2>Libri in <?= ucfirst(str_replace('_', ' ', $selectedGenre)); ?></h2>
            <?php foreach ($library[$selectedGenre] as $index => $book): ?>
              <div class="book">
                <form method="post">
                  <img src="<?= $book['image_url'] ?>" alt="<?= $book['NAME'] ?>" class="book-image">
                  <span class="book-title"><?= $book['NAME']; ?></span>
                  <span class="book-price"><?= number_format($book['prezzo'], 2); ?> €</span>
                  <input type="hidden" name="book" value="<?= $book['NAME']; ?>">
                  <input type="hidden" name="book_genre" value="<?= $selectedGenre; ?>">
                  <button type="submit" name="select_book">Prendi</button>
                </form>
              </div>
            <?php endforeach; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div
  </div>
</body>

</html>
