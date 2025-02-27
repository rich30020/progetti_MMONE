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

            $esistente = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);
            if ($esistente !== null) {
                // Se l'utente ha già votato, aggiorna il voto esistente
                return $this->votoModel->aggiornaVoto($user_id, $commento_id, $voto);
            } else {
                // Se l'utente non ha votato, aggiungi il nuovo voto
                return $this->votoModel->aggiungiVoto($user_id, $commento_id, $voto, $escursione_id);
            }

        } catch (Exception $e) {
            error_log("Errore nell'aggiungere o aggiornare il voto: " . $e->getMessage());
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

    public function getLikeDislikeCount($commento_id, $voto_type) {
        try {
            if ($commento_id <= 0 || ($voto_type !== 1 && $voto_type !== -1)) {
                throw new Exception("Parametri non validi.");
            }

            $count = $this->votoModel->getLikeDislikeCount($commento_id, $voto_type);

            return (int) $count;

        } catch (Exception $e) {
            error_log("Errore nel recupero del conteggio dei voti: " . $e->getMessage());
            return 0;
        }
    }

    public function getVotoPerCommentoUtente($user_id, $commento_id) {
        try {
            if ($user_id <= 0 || $commento_id <= 0) {
                throw new Exception("ID dell'utente o del commento non validi.");
            }

            $voto = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);

            return $voto !== null ? $voto : null;

        } catch (Exception $e) {
            error_log("Errore nel recupero del voto per il commento: " . $e->getMessage());
            return null;
        }
    }

    public function getUserVote($user_id, $commento_id) {
        try {
            if ($user_id <= 0 || $commento_id <= 0) {
                throw new Exception("ID dell'utente o del commento non validi.");
            }

            $voto = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);

            return $voto !== null ? $voto : 0;

        } catch (Exception $e) {
            error_log("Errore nel recupero del voto dell'utente per il commento: " . $e->getMessage());
            return 0;
        }
    }
}
?>
