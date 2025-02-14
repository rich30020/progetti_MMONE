<?php
require_once __DIR__ . '/../Model/Utente.php';

class UtentiController {

    private $utenteModel;

    public function __construct() {
        $this->utenteModel = new Utente();
    }

    // Recupera un utente per ID
    public function getUserById($userId) {
        try {
            return $this->utenteModel->getUserById($userId);
        } catch (Exception $e) {
            error_log("Errore nel recupero dell'utente: " . $e->getMessage());
            return null;
        }
    }
}
?>
