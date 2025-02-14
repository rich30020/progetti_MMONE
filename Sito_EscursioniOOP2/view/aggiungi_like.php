<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../Controller/CommentiController.php';

$commentiController = new CommentiController();

// Verifica che i dati siano stati inviati tramite POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commento_id'], $_POST['escursione_db'])) {
    $commento_id = (int) $_POST['commento_id'];
    $escursione_id = (int) $_POST['escursione_db'];
    $user_id = $_SESSION['user']['id'];

    // Aggiungi il "mi piace" per il commento
    if ($commentiController->aggiungiMiPiace($commento_id, $user_id, $escursione_id)) {
        // Recupera il commento aggiornato con il conteggio di mi piace e non mi piace
        $commento = $commentiController->getCommentoById($commento_id);

        // Restituisci i dati aggiornati come JSON
        header('Content-Type: application/json');
        echo json_encode([
            'like_count' => $commento['like_count'],
            'dislike_count' => $commento['dislike_count']
        ]);
    } else {
        // Se c'è stato un errore nell'aggiungere il "mi piace"
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Si è verificato un errore durante l\'aggiunta del mi piace.']);
    }
} else {
    // Se i parametri non sono stati inviati correttamente
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Dati incompleti.']);
}
?>
