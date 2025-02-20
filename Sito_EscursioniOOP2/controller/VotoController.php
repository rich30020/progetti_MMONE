<?php
require_once __DIR__ . '/../Model/Voto.php';

class VotoController {

    private $votoModel;

    public function __construct() {
        $this->votoModel = new Voto();
    }

    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        try {
            if ($commento_id <= 0 || $escursione_id <= 0) {
                throw new Exception("ID del commento o escursione non validi.");
            }

            if ($voto !== 1 && $voto !== -1) {
                throw new Exception("Il valore del voto deve essere 1 (Mi Piace) o -1 (Non Mi Piace).");
            }

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

    public function getVotiPerCommento($commento_id) {
        try {
            if ($commento_id <= 0) {
                throw new Exception("ID del commento non valido.");
            }

            $voti = $this->votoModel->getVotiPerCommento($commento_id);

            return $voti ? $voti : [];

        } catch (Exception $e) {
            error_log("Errore nel recupero dei voti: " . $e->getMessage());
            return [];
        }
    }

    public function getLikeDislikeCount($escursione_id) {
        try {
            if ($escursione_id <= 0) {
                throw new Exception("ID dell'escursione non valido.");
            }

            $likeCount = $this->votoModel->getLikeDislikeCount($escursione_id, 1);  
            $dislikeCount = $this->votoModel->getLikeDislikeCount($escursione_id, -1);  

            return [
                'like' => $likeCount,
                'dislike' => $dislikeCount
            ];

        } catch (Exception $e) {
            error_log("Errore nel recupero dei conteggi Mi Piace/Non Mi Piace: " . $e->getMessage());
            return [
                'like' => 0,
                'dislike' => 0
            ];
        }
    }
}
?>
