<?php
session_start();

if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    echo 'Utente non autenticato. Effettua il login per commentare.';
    exit();
}

require_once __DIR__ . '/../Controller/CommentiController.php';

if (!empty($_POST['escursione_id']) && !empty($_POST['commento']) && isset($_SESSION['user'])) {
    $escursione_id = intval($_POST['escursione_id']);
    $user_id = intval($_SESSION['user']['id']);
    $commento = trim($_POST['commento']);

    if (strlen($commento) < 3) {
        echo '<p class="error">Il commento deve contenere almeno 3 caratteri.</p>';
        exit();
    }

    $commentiController = new CommentiController();
    $successo = $commentiController->aggiungiCommento($escursione_id, $user_id, $commento);

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
