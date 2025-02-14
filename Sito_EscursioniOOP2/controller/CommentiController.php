<?php
require_once __DIR__ . '/../model/Commento.php';

class CommentiController {
    private $commentiModel;

    public function __construct() {
        $this->commentiModel = new Commenti();
    }

    // Metodo per aggiungere "mi piace" a un commento
    public function aggiungiMiPiace($commento_id, $user_id, $escursione_id) {
        return $this->commentiModel->aggiungiMiPiace($commento_id, $user_id, $escursione_id);
    }

    // Metodo per aggiungere "non mi piace" a un commento
    public function aggiungiNonMiPiace($commento_id, $user_id, $escursione_id) {
        return $this->commentiModel->aggiungiNonMiPiace($commento_id, $user_id, $escursione_id);
    }

    // Metodo per ottenere un commento per ID
    public function getCommentoById($commento_id) {
        return $this->commentiModel->getCommentoById($commento_id);
    }

    // Metodo per aggiungere un commento
    public function aggiungiCommento($escursione_id, $user_id, $commento) {
        return $this->commentiModel->aggiungiCommento($escursione_id, $user_id, $commento);
    }

    // Metodo per ottenere i commenti di un'escursione
    public function getCommenti($escursione_id) {
        return $this->commentiModel->getCommenti($escursione_id);
    }
}
