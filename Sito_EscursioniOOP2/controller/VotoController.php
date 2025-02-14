<?php
require_once __DIR__ . '/../Model/Voto.php';

class VotoController {

    private $votoModel;

    // Costruttore per istanziare il modello Voto
    public function __construct() {
        $this->votoModel = new Voto();
    }

    // Metodo per aggiungere un voto (Mi Piace o Non Mi Piace) a un commento
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        try {
            // Verifica che commento_id e escursione_id siano validi
            if ($commento_id <= 0 || $escursione_id <= 0) {
                throw new Exception("ID del commento o escursione non validi.");
            }

            // Verifica che il voto sia valido (1 per Mi Piace, -1 per Non Mi Piace)
            if ($voto !== 1 && $voto !== -1) {
                throw new Exception("Il valore del voto deve essere 1 (Mi Piace) o -1 (Non Mi Piace).");
            }

            // Aggiungi il voto tramite il modello
            $result = $this->votoModel->aggiungiVoto($user_id, $commento_id, $voto, $escursione_id);

            if (!$result) {
                throw new Exception("Errore nell'aggiungere il voto al commento.");
            }

            return true;

        } catch (Exception $e) {
            error_log("Errore nell'aggiungere il voto: " . $e->getMessage());
            return false;
        }
    }

    // Metodo per ottenere i voti (Mi Piace, Non Mi Piace) per un commento
    public function getVotiPerCommento($commento_id) {
        try {
            if ($commento_id <= 0) {
                throw new Exception("ID del commento non valido.");
            }

            // Ottieni i voti per il commento dal modello
            $voti = $this->votoModel->getVotiPerCommento($commento_id);

            // Se non ci sono voti, ritorna un array vuoto
            return $voti ? $voti : [];

        } catch (Exception $e) {
            error_log("Errore nel recupero dei voti: " . $e->getMessage());
            return [];  // Restituisce un array vuoto se c'è un errore
        }
    }

    // Metodo per ottenere il conteggio dei "Mi Piace" e "Non Mi Piace" per una escursione
    public function getLikeDislikeCount($escursione_id) {
        try {
            if ($escursione_id <= 0) {
                throw new Exception("ID dell'escursione non valido.");
            }

            // Ottieni il conteggio dei "Mi Piace" (tipo 1) e dei "Non Mi Piace" (tipo -1)
            $likeCount = $this->votoModel->getLikeDislikeCount($escursione_id, 1);  // Mi Piace
            $dislikeCount = $this->votoModel->getLikeDislikeCount($escursione_id, -1);  // Non Mi Piace

            return [
                'like' => $likeCount,
                'dislike' => $dislikeCount
            ];

        } catch (Exception $e) {
            error_log("Errore nel recupero dei conteggi Mi Piace/Non Mi Piace: " . $e->getMessage());
            return [
                'like' => 0,
                'dislike' => 0
            ];  // Restituisce conteggi a zero in caso di errore
        }
    }
}
?>
