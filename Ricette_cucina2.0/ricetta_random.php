<?php
session_start(); 

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html"); // Reindirizza se non loggato
  exit;
}

include 'connessione_ricette.php';

// Funzione per ottenere ricette casuali
function get_random_recipes($conn_kitchen) {
    $sql = "SELECT * FROM ricette ORDER BY RAND() LIMIT 3"; 
    $result = $conn_kitchen->query($sql);

    $recipes = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $recipes[] = $row; // Aggiungi ogni ricetta all'array
        }
    }
    return $recipes;
}

// Controlla se la richiesta è AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $randomRecipes = get_random_recipes($conn_kitchen);
    echo json_encode(['recipes' => $randomRecipes]);
    $conn_kitchen->close();
    exit;
}

// Caricamento normale della pagina
$randomRecipes = get_random_recipes($conn_kitchen);
if ($randomRecipes) {
    header("Location: ricetta.php?recipes_id=" . $randomRecipes[0]['id']); // Reindirizza alla prima ricetta casuale
    exit;
} else {
    die("Errore: Nessuna ricetta trovata.");
}

$conn_kitchen->close(); 
?>
