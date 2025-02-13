<?php
require_once __DIR__ . '/../Model/Utente.php'; // Aggiungi il percorso corretto

class UtentiController {

    private $utenteModel;

    public function __construct() {
        $this->utenteModel = new Utente(); // Instanzia il modello Utente
    }

    // Recupera un utente per ID
    public function getUserById($userId) {
        return $this->utenteModel->getUserById($userId); // Passa il controllo al modello Utente
    }
}
?>
