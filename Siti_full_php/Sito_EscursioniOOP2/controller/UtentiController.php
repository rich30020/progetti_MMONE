<?php
require_once __DIR__ . '/../Model/Utente.php';

class UtentiController {
    private $utenteModel;

    public function __construct() {
        $this->utenteModel = new Utente();
    }

    public function getUserById($userId) {
        try {
            return $this->utenteModel->getUserById($userId);
        } catch (Exception $e) {
            error_log("Errore nel recuperare i dettagli dell'utente: " . $e->getMessage());
            return null;
        }
    }

    public function updateProfile($userId, $nome, $email, $eta, $livello_esperienza) {
        try {
            return $this->utenteModel->updateProfile($userId, $nome, $email, $eta, $livello_esperienza);
        } catch (Exception $e) {
            error_log("Errore nel modificare il profilo: " . $e->getMessage());
            return false;
        }
    }
}
?>
