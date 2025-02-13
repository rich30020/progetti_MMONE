<?php
require_once __DIR__ . '/../Model/Voto.php';

class VotoController {

    // Metodo per aggiungere un voto
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_db) {
        // Verifica che commento_id e escursione_db siano validi
        if ($commento_id <= 0 || $escursione_db <= 0) {
            throw new Exception("ID del commento o escursione non validi.");
        }

        $votoModel = new Voto();
        $votoModel->aggiungiVoto($user_id, $commento_id, $voto, $escursione_db);
    }
}
?>
