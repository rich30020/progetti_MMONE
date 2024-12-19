<?php
session_start();
if(!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
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
    'science_fiction' => []
];

// Funzione per aggiungere libri alla libreria
function addBook(&$library, $genre, $name) {
    $book = [
        'name' => $name
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
            fputcsv($file, [$genre, $book['name']]);
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
    while (($data = fgetcsv($file)) !== false) {
        $library[$data[0]][] = ['name' => $data[1]];
    }
    fclose($file);

    return $library;
}

// Carica la libreria dal CSV se la sessione non è impostata
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

    if (isset($_POST['reset'])) {
        // Ripristina la libreria iniziale e salva nel CSV
        $library = [
            'historical_novel' => [],
            'adventure_action' => [],
            'fantasy' => [],
            'science_fiction' => []
        ];

        // Aggiungi libri di esempio
        addBook($library, 'historical_novel', 'La Caduta di Troia');
        addBook($library, 'historical_novel', 'L\'Impero Romano');
        addBook($library, 'historical_novel', 'Guerra e Pace');
        addBook($library, 'historical_novel', 'I Miserabili');

        addBook($library, 'adventure_action', 'Il Tesoro dell\'Isola');
        addBook($library, 'adventure_action', 'Indiana Jones');
        addBook($library, 'adventure_action', 'Lara Croft');
        addBook($library, 'adventure_action', 'Il Gladiatore');

        addBook($library, 'fantasy', 'Il Signore degli Anelli');
        addBook($library, 'fantasy', 'Harry Potter');
        addBook($library, 'fantasy', 'Le Cronache di Narnia');
        addBook($library, 'fantasy', 'Il Trono di Spade');

        addBook($library, 'science_fiction', 'Dune');
        addBook($library, 'science_fiction', 'Blade Runner');
        addBook($library, 'science_fiction', '1984');
        addBook($library, 'science_fiction', 'Il Mondo Nuovo');

        saveLibraryToCSV($filename, $library);
        $_SESSION['library'] = $library;
        unset($_SESSION['selected_genres']);
        header("Refresh:0");
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css" class="css">
    <title>Gestione Libreria</title>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <h2>Tipologie di Libri</h2>
            <form method="post">
                <input type="hidden" name="genre" value="historical_novel">
                <button type="submit" name="select_genre">Romanzi Storici</button>
            </form>
            <form method="post">
                <input type="hidden" name="genre" value="adventure_action">
                <button type="submit" name="select_genre">Avventura e Azione</button>
            </form>
            <form method="post">
                <input type="hidden" name="genre" value="fantasy">
                <button type="submit" name="select_genre">Fantasy</button>
            </form>
            <form method="post">
                <input type="hidden" name="genre" value="science_fiction">
                <button type="submit" name="select_genre">Fantascienza</button>
            </form>
            <form method="post" style="margin-top: 20px;">
                <button type="submit" name="reset" class="reset-btn">Ricomponi Libreria</button>
            </form>
        </div>
        <div class="main">
            <h1>Gestione Libreria</h1>
            <p>Ciao <?php echo $_SESSION["username"]; ?>, seleziona una categoria di libri!</p>
            <?php if (isset($_SESSION['selected_genres'])): ?>
                <div class="library-container">
                <?php foreach ($_SESSION['selected_genres'] as $selectedGenre): ?>
                        <h2>Libri in <?= ucfirst(string: str_replace('_', ' ', $selectedGenre)); ?></h2>
                        <?php foreach ($library[$selectedGenre] as $index => $book): ?>
                            <div class="book">
                                <form method="post">
                                    <span><?= $book['name']; ?></span>
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
