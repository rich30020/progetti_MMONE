<?php
session_start();
if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html");
  exit;
}

// Nome del file CSV per memorizzare i dati della libreria
$filename = 'library.csv';


// Inizializza la struttura della libreria
$library = [
  'historical_novel' => [],
  'adventure_action' => [],
  'fantasy' => [],
  'science_fiction' => [],
  'horror' => [],
];

// Funzione per aggiungere libri alla libreria
function addBook(&$library, $genre, $name, $imageUrl) {
  $book = [
    'name' => $name,
    'image_url' => $imageUrl, // Store the image URL directly
  ];

  if (array_key_exists($genre, $library)) {
    $library[$genre][] = $book;
  } else {
    echo "Genere non trovato nella libreria.";
  }
}

// Funzione per salvare lo stato della libreria nel file CSV
function saveLibraryToCSV($filename, $library) {
  $file = fopen($filename, 'w');
  foreach ($library as $genre => $books) {
    foreach ($books as $book) {
      fputcsv($file, [$genre, $book['name'], $book['image_url']]); // Include image URL in CSV
    }
  }
  fclose($file);
}

// Funzione per caricare la libreria dal file CSV
function loadLibraryFromCSV($filename) {
  global $library;
  if (!file_exists($filename)) {
    return $library;
  }
  $file = fopen($filename, 'r');

  // legge tutte le righe
  while (($data = fgetcsv($file)) !== false) {
    // Per ogni riga, aggiunge un nuovo elemento all'array $library. name=>[1]
    $library[$data[0]][] = ['name' => $data[1], 'image_url' => $data[2]]; // Include image URL as array element
  }
  fclose($file);

  return $library;
}

// Carica la libreria dal CSV
if (!isset($_SESSION['library'])) {
  $_SESSION['library'] = loadLibraryFromCSV($filename);
}
$library = &$_SESSION['library'];


// Gestisci azioni di POST (selezione del libro o reset della libreria)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['select_genre']) && isset($_POST['genre'])) {
    $selectedGenre = $_POST['genre'];
    if (!isset($_SESSION['selected_genres'])) {
      $_SESSION['selected_genres'] = [];
    }
    if (!in_array($selectedGenre, $_SESSION['selected_genres'])) {
      $_SESSION['selected_genres'][] = $selectedGenre;
    }
  }

  if (isset($_POST['select_book']) && isset($_POST['book']) && isset($_POST['book_genre'])) {
    $selectedBook = $_POST['book'];
    $bookGenre = $_POST['book_genre'];

    // Rimuove il libro selezionato dalla libreria
    if (isset($library[$bookGenre][$selectedBook])) {
      array_splice($library[$bookGenre], $selectedBook, 1);
      saveLibraryToCSV($filename, $library);
      echo "<p>Il libro è stato preso!</p>";
    }
    }
  }

  if (isset($_POST['reset'])) {
    // Ripristina la libreria iniziale e salva nel CSV
    $library = [
      'historical_novel' => [],
      'adventure_action' => [],
      'fantasy' => [],
      'science_fiction' => [],
      'horror' => [],
    ];

    // Aggiungi libri di esempio con immagini
        addBook($library, 'historical_novel', 'La Caduta di Troia', 'https://edicola.shop/media/catalog/product/cache/1/thumbnail/400x/17f82f742ffe127f42dca9de82fb58b1/d/c/dc57953757.jpg');
        addBook($library, 'historical_novel', "L'Impero Romano", "https://www.laterza.it/immagini/copertine-big/9788858155301.jpg");
        addBook($library, 'historical_novel', 'Guerra e Pace', "https://www.ibs.it/images/9788811686200_0_536_0_75.jpg");
        addBook($library, 'historical_novel', 'I Miserabili', "https://www.ibs.it/images/9788817129107_0_536_0_75.jpg");
        addBook($library, 'historical_novel', 'Promessi Sposi', "https://m.media-amazon.com/images/I/91npmGWLGeL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'historical_novel', 'Il Nome della Rosa', "https://m.media-amazon.com/images/I/61Aa9Yic8AL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'historical_novel', 'I Pilastri della Terra', "https://www.ibs.it/images/9788804666929_0_536_0_75.jpg");
        addBook($library, 'historical_novel', 'Memorie di Adriano', "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRRsJwCreCdCVgjgWmABGTN-CnkTK_4JeUjlw&s");

        addBook($library, 'adventure_action', "Il Tesoro dell L'Isola", "https://www.orecchioacerbo.it/wp-content/uploads/2021/08/isola-del-tesoro.jpg");
        addBook($library, 'adventure_action', 'Indiana Jones', "https://m.media-amazon.com/images/I/91s3m--k3BL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'adventure_action', 'Lara Croft', "https://www.ibs.it/images/9788863554618_0_536_0_75.jpg");
        addBook($library, 'adventure_action', 'Il Gladiatore', "https://www.ibs.it/images/9788809885394_0_536_0_75.jpg");
        addBook($library, 'adventure_action', 'Il Nome della Rosa', "https://m.media-amazon.com/images/I/61Aa9Yic8AL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'adventure_action', 'Il Codice da Vinci', "https://www.ibs.it/images/9788804667223_0_536_0_75.jpg");
        addBook($library, 'adventure_action', 'Il Trono di Spade', "https://www.ibs.it/images/9788804711957_0_536_0_75.jpg");
        addBook($library, 'adventure_action', 'La Ragazza con il Tatuaggio del Drago', "https://m.media-amazon.com/images/I/61DR4grQBRL._AC_UF1000,1000_QL80_.jpg");

        addBook($library, 'fantasy', 'Il Signore degli Anelli', "https://m.media-amazon.com/images/I/81HXjSgmsvL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'fantasy', 'Harry Potter', "https://www.ibs.it/images/9788867155958_0_424_0_75.jpg");
        addBook($library, 'fantasy', 'Le Cronache di Narnia', "https://m.media-amazon.com/images/I/A1yPOdvMgJL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'fantasy', 'Il Trono di Spade', "https://www.ibs.it/images/9788804711957_0_536_0_75.jpg");
        addBook($library, 'fantasy', 'La Ruota del Tempo', "https://www.lafeltrinelli.it/images/9788834739525_0_536_0_75.jpg");
        addBook($library, 'fantasy', 'Il Nome del Vento', "https://m.media-amazon.com/images/I/91SKJUew85L._UF1000,1000_QL80_.jpg");
        addBook($library, 'fantasy', "La Bussola d\'Oro", "https://m.media-amazon.com/images/I/71FRPjGCKzL._AC_UF1000,1000_QL80_.jpg");
        
        addBook($library, 'science_fiction', 'Dune', "https://www.lafeltrinelli.it/images/9788834739679_0_536_0_75.jpg");
        addBook($library, 'science_fiction', 'Blade Runner', "https://m.media-amazon.com/images/I/81YMbFJ6wpL.jpg");
        addBook($library, 'science_fiction', '1984', "https://www.ibs.it/images/9788804668237_0_536_0_75.jpg");
        addBook($library, 'science_fiction', 'Il Mondo Nuovo', "https://www.ibs.it/images/9788804735823_0_536_0_75.jpg");
        addBook($library, 'science_fiction', 'I Figli di Andromeda', "https://www.amantideilibri.it/wp-content/uploads/2022/10/il-gioco-di-andromeda.jpg");
        addBook($library, 'science_fiction', 'La Guerra dei Mondi', "https://m.media-amazon.com/images/I/81hNSE-OwLL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'science_fiction', 'Neuromante', "https://m.media-amazon.com/images/I/71jvNwlSt0L._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'science_fiction', 'Il Marziano', "https://m.media-amazon.com/images/I/81S34bHUE-L._AC_UF1000,1000_QL80_.jpg");

        addBook($library, 'horror', 'Dracula', "https://m.media-amazon.com/images/I/61uwJXxPwuL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'horror', 'Frankenstein', "https://m.media-amazon.com/images/I/61oPJXXqXGL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'horror', 'Il Ritratto di Dorian Gray', "https://m.media-amazon.com/images/I/71c5s0ykyIL._AC_UF1000,1000_QL80_.jpg");
        addBook($library, 'horror', "L\'Incubo di Hill House", "https://www.adelphi.it/spool/i__id6551_mw600__1x.jpg");
        addBook($library, 'horror', 'Il Castello di Otranto', "https://www.ibs.it/images/9788807902161_0_536_0_75.jpg");
        addBook($library, 'horror', 'Jane Eyre', "https://www.ibs.it/images/9788807900778_0_536_0_75.jpg");
        addBook($library, 'horror', 'Il Fantasma di Canterville', "https://www.ibs.it/images/9788807900570_0_536_0_75.jpg");
        addBook($library, 'horror', 'Carmilla', "https://m.media-amazon.com/images/I/711Wx9cjUrL._AC_UF1000,1000_QL80_.jpg");

        saveLibraryToCSV($filename, $library);
        $_SESSION['library'] = $library;
        unset($_SESSION['selected_genres']);
        header("Refresh:0");
    }      
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

            <!-- Se tutti i libri vengono terminati -->
            <?php 
            $allBooksFinished = count($library['historical_novel']) === 0 &&
                                count($library['adventure_action']) === 0 &&
                                count($library['fantasy']) === 0 &&
                                count($library['science_fiction']) === 0 &&
                                count($library['horror']) === 0;

            if ($allBooksFinished): ?>
                <p>Tutti i libri sono terminati. Per ricominciare, clicca sul pulsante qui sotto.</p>
                <form method="post" style="margin-top: 20px;">
                    <button type="submit" name="reset" class="reset-btn">Ricomponi Libreria</button>
                </form>
            

            <!-- Se i libri di una specifica libreria terminano-->
            <?php else: ?>
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
                <form method="post" style="margin-top: 20px;">
                    <button type="submit" name="reset" class="reset-btn">Ricomponi Libreria</button>
                </form>
            <?php endif; ?>
        </div>
        <div class="main">
            <h1>Gestione Libreria</h1>
            <p>Ciao <?php echo $_SESSION["username"]; ?>, seleziona una categoria di libri!</p>
            <?php if (isset($_SESSION['selected_genres'])): ?>
                <div class="library-container">
                    <?php foreach ($_SESSION['selected_genres'] as $selectedGenre): ?>
                        <h2>Libri in <?= ucfirst(str_replace('_', ' ', $selectedGenre)); ?></h2>
                        <?php foreach ($library[$selectedGenre] as $index => $book): ?>
                            <div class="book">
                            <form method="post">
                                <img src="<?= $book['image_url'] ?>" alt="<?= $book['name'] ?>" class="book-image">
                                <span class="book-title"><?= $book['name']; ?></span>
                                <input type="hidden" name="book" value="<?= $index; ?>">
                                <input type="hidden" name="book_genre" value="<?= $selectedGenre; ?>">
                                <button type="submit" name="select_book">Prendi</button>
                            </form>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

