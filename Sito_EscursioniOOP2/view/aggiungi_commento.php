<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    echo 'Utente non autenticato. Effettua il login per commentare.';
    exit();
}

// Includiamo il controller dei commenti
require_once __DIR__ . '/../Controller/CommentiController.php';

if (!empty($_POST['escursione_id']) && !empty($_POST['commento']) && isset($_SESSION['user'])) {
    $escursione_id = intval($_POST['escursione_id']);
    $user_id = intval($_SESSION['user']['id']);
    $commento = trim($_POST['commento']);

    // Verifica se il commento ha almeno 3 caratteri
    if (strlen($commento) < 3) {
        echo '<p class="error">Il commento deve contenere almeno 3 caratteri.</p>';
        exit();
    }

    // Creiamo un'istanza del controller dei commenti
    $commentiController = new CommentiController();
    $successo = $commentiController->aggiungiCommento($escursione_id, $user_id, $commento);

    // Se il commento è stato aggiunto con successo
    if ($successo) {
        header('Location: esplora.php?id=' . $escursione_id . '&success=true');
        exit();
    } else {
        echo '<p class="error">Errore nell\'aggiungere il commento.</p>';
    }
} else {
    echo '<p class="error">Dati mancanti o utente non autenticato.</p>';
}
?>
