<?php
require_once __DIR__ . '/../Model/Escursione.php';
require_once __DIR__ . '/../Model/Utente.php';
require_once __DIR__ . '/../Model/Voto.php';
require_once __DIR__ . '/../controller/VotoController.php';

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

    // Ottieni un'escursione per ID
    public function getEscursioneById($id) {
        return $this->escursioneModel->getEscursioneById($id);
    }

    // Aggiungi una nuova escursione
    public function aggiungiEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        return $this->escursioneModel->insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto);
    }

    // Ottieni il conteggio dei Like e Dislike per commento specifico
    public function getLikeDislikeCount($commento_id, $voto_type) {
        return $this->votoModel->getLikeDislikeCount($commento_id, $voto_type);
    }

    // Ottieni le informazioni dell'utente tramite ID
    public function getUserById($userId) {
        return $this->utenteModel->getUserById($userId);
    }

    // Gestisci Mi Piace per un commento
    public function gestisciMiPiace($commento_id, $user_id, $tipo_voto, $escursione_id) {
        try {
            // Verifica se esiste già un voto per il commento e l'utente
            $voto = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);

            if ($voto !== null) {
                // Se il voto esiste e il tipo di voto è diverso, aggiorna il voto
                if ($voto != $tipo_voto) {
                    return $this->votoModel->aggiornaVoto($user_id, $commento_id, $tipo_voto);
                }
                // Se il voto è uguale, non fare nulla
                return false;
            } else {
                // Se il voto non esiste, aggiungi un nuovo voto
                return $this->votoModel->aggiungiVoto($user_id, $commento_id, $tipo_voto, $escursione_id);
            }
        } catch (Exception $e) {
            error_log("Errore nella gestione del Mi Piace: " . $e->getMessage());
            return false;
        }
    }

    // Ottieni il conteggio complessivo dei Like e Dislike per una escursione
    public function getLikeDislikeCountPerEscursione($escursione_id) {
        try {
            // Restituisce i conteggi di Mi Piace e Non Mi Piace per una escursione
            return [
                'mi_piace' => $this->votoModel->getLikeDislikeCount($escursione_id, 1),
                'non_mi_piace' => $this->votoModel->getLikeDislikeCount($escursione_id, 0)
            ];
        } catch (Exception $e) {
            error_log("Errore nel recupero dei conteggi Mi Piace/Non Mi Piace per escursione: " . $e->getMessage());
            return ['mi_piace' => 0, 'non_mi_piace' => 0]; // Restituisci 0 in caso di errore
        }
    }
}
?>
