<?php
require_once __DIR__ . '/../Model/Commento.php';

class CommentiController {

    // Aggiungi un commento
    public function aggiungiCommento($escursione_id, $user_id, $commento) {
        $commentoModel = new Commento();
        return $commentoModel->aggiungiCommento($user_id, $escursione_id, $commento);
    }

    // Recupera i commenti per una specifica escursione
    public function getCommenti($escursione_id) {
        $commentoModel = new Commento();
        return $commentoModel->getCommenti($escursione_id);
    }
}
?>
