<?php
session_start();

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("location: login.html"); // Reindirizza se non loggato
    exit;
}

include 'connessione_ricette.php';

// Carica tutte le ricette
$sql = "SELECT * FROM ricette";
$result = $conn_kitchen->query($sql);

// Inizializza un array per memorizzare le ricette
$randomRecipes = [];
if ($result->num_rows > 0) {
    $library = [];
    while ($row = $result->fetch_assoc()) {
        $library[] = [
            'id' => $row['id'],
            'nome' => $row['nome'],
            'descrizione' => $row['descrizione'],
            'image_url' => $row['image_url'],
            'tempo_di_preparazione' => $row['tempo_di_preparazione'],
            'grado_di_difficolta' => $row['grado_di_difficolta']
        ];
    }

    // Seleziona fino a 3 ricette casuali
    if (count($library) > 3) {
        $randomKeys = array_rand($library, 3);
        foreach ($randomKeys as $key) {
            $randomRecipes[] = $library[$key];
        }
    } else {
        $randomRecipes = $library;
    }
}

// Restituisce le ricette casuali come JSON
echo json_encode(['randomRecipes' => $randomRecipes]);

$conn_kitchen->close();
?>
