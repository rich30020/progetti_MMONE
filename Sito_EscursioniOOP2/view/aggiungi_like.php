<?php
require_once __DIR__ . '/../Controller/VotoController.php';

if (isset($_POST['commento_id']) && isset($_POST['user_id']) && isset($_POST['escursione_db'])) {
    $commento_id = $_POST['commento_id'];
    $user_id = $_POST['user_id'];
    $voto = 1; // Mi Piace (puoi usare -1 per Non Mi Piace)
    $escursione_db = $_POST['escursione_db']; // ID escursione associata al voto

    // Verifica che commento_id e escursione_db siano numeri positivi
    if ($commento_id > 0 && $escursione_db > 0) {
        $votoController = new VotoController();
        try {
            $votoController->aggiungiVoto($user_id, $commento_id, $voto, $escursione_db);
            echo "Voto aggiunto con successo!";
        } catch (Exception $e) {
            echo "Errore: " . $e->getMessage();
        }
    } else {
        echo "Errore: commento_id o escursione_db non valido.";
    }
} else {
    echo "Errore: Dati mancanti.";
}
?>
