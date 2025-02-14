<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    echo json_encode(['error' => 'Utente non autenticato. Effettua il login per commentare.']);
    exit();
}

require_once __DIR__ . '/../Controller/CommentiController.php';

$commentiController = new CommentiController();

// Ottieni i dati dalla richiesta
$commento_id = isset($_POST['commento_id']) ? (int)$_POST['commento_id'] : 0;
$escursione_id = isset($_POST['escursione_id']) ? (int)$_POST['escursione_id'] : 0;
$user_id = $_SESSION['user']['id'];

// Controlla se i parametri necessari sono presenti
if ($commento_id === 0 || $escursione_id === 0 || !$user_id) {
    echo json_encode(['error' => 'Parametri mancanti']);
    exit();
}

// Aggiungi il "non mi piace"
if ($commentiController->aggiungiNonMiPiace($commento_id, $user_id, $escursione_id)) {
    // Ottieni il numero di "mi piace" e "non mi piace" aggiornati per il commento
    $commento = $commentiController->getCommentoById($commento_id);

    echo json_encode([
        'like_count' => $commento['like_count'], 
        'dislike_count' => $commento['dislike_count']
    ]);
} else {
    echo json_encode(['error' => 'Errore nell\'aggiunta del "non mi piace"']);
}
?>
