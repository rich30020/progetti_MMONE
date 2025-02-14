<?php
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['user']['id'])) {
    echo "Utente non loggato";
    exit;
}

require_once __DIR__ . '/../Controller/CommentiController.php';

// Ottieni i dati del voto
$commento_id = isset($_POST['commento_id']) ? (int)$_POST['commento_id'] : 0;
$escursione_db = isset($_POST['escursione_db']) ? (int)$_POST['escursione_db'] : 0;
$user_id = $_SESSION['user']['id'];
$voto = 1; // Mi Piace

// Verifica che i dati siano validi
if ($commento_id <= 0 || $escursione_db <= 0) {
    echo "Errore: ID del commento o dell'escursione non valido.";
    exit;
}

// Instanzia il controller del commento
$commentoController = new CommentiController();

// Verifica se l'utente ha già messo "Non Mi Piace"
if ($commentoController->hasDisliked($user_id, $commento_id)) {
    // Rimuovi il "Non Mi Piace" e aggiungi il "Mi Piace"
    $commentoController->removeDislike($user_id, $commento_id);
}

$commentoController->addLike($user_id, $commento_id);

// Ottieni i nuovi contatori di Mi Piace e Non Mi Piace
$contatori = $commentoController->getLikeDislikeCount($commento_id);

echo "Mi Piace: " . $contatori['mi_piace'] . "<br>";
echo "Non Mi Piace: " . $contatori['non_mi_piace'] . "<br>";

exit;

?>