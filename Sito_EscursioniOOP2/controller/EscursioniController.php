<?php
// Importazione dei modelli e controller necessari
require_once __DIR__ . '/../Model/Escursione.php';
require_once __DIR__ . '/../Model/Utente.php';
require_once __DIR__ . '/../Model/Voto.php';
require_once __DIR__ . '/../controller/VotoController.php';

// Definizione della classe EscursioniController
class EscursioniController {
    private $escursioneModel;
    private $utenteModel;
    private $votoModel;

    public function __construct() {
        // Inizializzazione dei modelli
        $this->escursioneModel = new Escursione();
        $this->utenteModel = new Utente();
        $this->votoModel = new Voto();
    }

    // Ottieni tutte le escursioni
    public function getEscursioni() {
        return $this->escursioneModel->getAllEscursioni();
    }

    // Ottieni l'escursione per ID
    public function getEscursioneById($id) {
        return $this->escursioneModel->getEscursioneById($id);
    }

    // Aggiungi una nuova escursione
    public function aggiungiEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        return $this->escursioneModel->insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto);
    }

    // Ottieni il conteggio di Mi Piace e Non Mi Piace per tipo di voto
    public function getLikeDislikeCount($escursione_id, $voto_type) {
        return $this->votoModel->getLikeDislikeCount($escursione_id, $voto_type);
    }

    // Ottieni l'utente per ID
    public function getUserById($userId) {
        return $this->utenteModel->getUserById($userId);
    }

    // Ottieni il conteggio di Mi Piace e Non Mi Piace per escursione
    public function getLikeDislikeCountPerEscursione($escursione_id) {
        try {
            // Crea una nuova istanza di VotoController
            $votoController = new VotoController();
            
            // Recupera il conteggio dei voti per l'escursione
            $conteggi = $votoController->getLikeDislikeCount($escursione_id);
            
            return $conteggi;
        } catch (Exception $e) {
            // Log dell'errore se si verifica un problema
            error_log("Errore nel recupero dei conteggi Mi Piace/Non Mi Piace per escursione: " . $e->getMessage());
            
            // Restituisce conteggi a zero in caso di errore
            return [
                'like' => 0,
                'dislike' => 0
            ];
        }
    }
}
?>
