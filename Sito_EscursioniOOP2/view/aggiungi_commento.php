<?php
session_start();

// Verifica se l'utente è autenticato
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    echo 'Utente non autenticato. Effettua il login per commentare.';
    exit();
}

require_once __DIR__ . '/../Controller/CommentiController.php';

if (isset($_POST['escursione_id'], $_POST['commento'], $_SESSION['user'])) {
    $escursione_id = $_POST['escursione_id'];
    $user_id = $_SESSION['user']['id'];
    $commento = $_POST['commento'];

    $commentiController = new CommentiController();
    $successo = $commentiController->aggiungiCommento($escursione_id, $user_id, $commento);

    if ($successo) {
        // Reindirizza alla pagina esplora con il messaggio di successo
        header('Location: esplora.php?id=' . $escursione_id . '&success=true');
        exit();
    } else {
        echo '<p class="error">Errore nell\'aggiungere il commento.</p>';
    }
} else {
    echo '<p class="error">Dati mancanti o utente non autenticato.</p>';
}
?>
