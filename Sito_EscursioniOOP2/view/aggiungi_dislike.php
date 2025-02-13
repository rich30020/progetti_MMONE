<?php
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['utente_id'])) {
    echo "Utente non loggato";
    exit;
}

require_once __DIR__ . '/../Controller/VotoController.php';

// Ottieni l'ID del commento, dell'escursione e l'ID dell'utente
$commento_id = isset($_POST['commento_id']) ? (int)$_POST['commento_id'] : 0;
$escursione_db = isset($_POST['escursione_db']) ? (int)$_POST['escursione_db'] : 0;
$user_id = $_SESSION['utente_id'];
$voto = -1; // Non Mi Piace

// Verifica che i dati siano validi
if ($commento_id <= 0 || $escursione_db <= 0) {
    echo "Errore: ID del commento o dell'escursione non valido.";
    exit;
}

// Instanzia il controller del voto
$votoController = new VotoController();

// Aggiungi il voto (Non Mi Piace)
try {
    $votoController->aggiungiVoto($user_id, $commento_id, $voto, $escursione_db);
} catch (Exception $e) {
    echo "Errore: " . $e->getMessage();
    exit;
}

// Ora recuperiamo i dati aggiornati dei voti
require_once __DIR__ . '/../Model/Voto.php';
$votoModel = new Voto();

// Usa il metodo per ottenere i conteggi dei Mi Piace e Non Mi Piace
$voti = $votoModel->getLikeDislikeCount($commento_id, $voto);

// Mostra i voti aggiornati sulla stessa pagina
echo "Mi Piace: " . $voti['mi_piace'] . "<br>";
echo "Non Mi Piace: " . $voti['non_mi_piace'] . "<br>";
exit;
?>
