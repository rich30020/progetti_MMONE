<?php
session_start();

// Verifica se l'utente è autenticato (se la variabile di sessione 'user' è settata)
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    echo 'Utente non autenticato. Effettua il login per commentare.';
    exit(); // Ferma l'esecuzione del codice se l'utente non è autenticato
}

require_once __DIR__ . '/../Controller/CommentiController.php';

if (isset($_POST['escursione_id']) && isset($_POST['commento']) && isset($_SESSION['user'])) {
    $escursione_id = $_POST['escursione_id'];
    $user_id = $_SESSION['user']['id']; // Usa l'utente dalla sessione
    $commento = $_POST['commento'];

    $commentiController = new CommentiController();
    $successo = $commentiController->aggiungiCommento($escursione_id, $user_id, $commento);

    if ($successo) {
        // Reindirizza alla pagina esplora con il messaggio di successo
        header('Location: esplora.php?id=' . $escursione_id);
        exit();
    } else {
        echo 'Errore nell\'aggiungere il commento.';
    }
} else {
    echo 'Dati mancanti o utente non autenticato.';
}
?>
