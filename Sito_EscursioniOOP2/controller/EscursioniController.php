<?php
require_once __DIR__ . '/../Model/Escursione.php';
require_once __DIR__ . '/../Model/Utente.php';
require_once __DIR__ . '/../Model/Voto.php'; // Assicurati di includere il modello Voto

class EscursioniController {
    private $escursioneModel;
    private $utenteModel;
    private $votoModel; // Modello Voto

    public function __construct() {
        $this->escursioneModel = new Escursione();
        $this->utenteModel = new Utente();
        $this->votoModel = new Voto(); // Inizializza il modello Voto
    }

    // Ottieni tutte le escursioni
    public function getEscursioni() {
        return $this->escursioneModel->getAllEscursioni();
    }

    // Ottieni l'escursione per ID
    public function getEscursioneById($id) {
        return $this->escursioneModel->getEscursioneById($id);
    }

    // Aggiungi una nuova escursione e restituisci l'ID dell'escursione inserita
    public function aggiungiEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        return $this->escursioneModel->insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto);
    }

    // Ottieni un utente per ID
    public function getUserById($userId) {
        return $this->utenteModel->getUserById($userId);
    }

    // Nuovo metodo per ottenere i conteggi di Mi Piace e Non Mi Piace
    public function getLikeDislikeCount($escursione_id, $voto_type) {
        return $this->votoModel->getLikeDislikeCount($escursione_id, $voto_type);
    }
}
?>
