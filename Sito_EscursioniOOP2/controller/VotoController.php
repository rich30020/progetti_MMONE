<?php
require_once __DIR__ . '/../Model/Voto.php';

class VotoController {

    private $votoModel;

    public function __construct() {
        // Crea una nuova istanza del modello Voto
        $this->votoModel = new Voto();
    }

    /**
     * Aggiungi un voto a un commento
     *
     * @param int $user_id ID dell'utente
     * @param int $commento_id ID del commento
     * @param int $voto Voto (1 per Mi Piace, -1 per Non Mi Piace)
     * @param int $escursione_id ID dell'escursione
     * @return bool Risultato dell'operazione
     */
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_id) {
        try {
            // Verifica la validità degli ID
            if ($commento_id <= 0 || $escursione_id <= 0) {
                throw new Exception("ID del commento o escursione non validi.");
            }

            // Verifica il valore del voto
            if ($voto !== 1 && $voto !== -1) {
                throw new Exception("Il valore del voto deve essere 1 (Mi Piace) o -1 (Non Mi Piace).");
            }

            // Aggiungi il voto tramite il modello
            $result = $this->votoModel->aggiungiVoto($user_id, $commento_id, $voto, $escursione_id);

            // Se l'operazione non è andata a buon fine, solleva un'eccezione
            if (!$result['success']) {
                throw new Exception($result['message']);
            }

            return true;

        } catch (Exception $e) {
            // Registra l'errore nel log
            error_log("Errore nell'aggiungere il voto: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Recupera tutti i voti associati a un commento
     *
     * @param int $commento_id ID del commento
     * @return array Lista dei voti
     */
    public function getVotiPerCommento($commento_id) {
        try {
            // Verifica la validità dell'ID del commento
            if ($commento_id <= 0) {
                throw new Exception("ID del commento non valido.");
            }

            // Ottieni i voti dal modello
            $voti = $this->votoModel->getVotiPerCommento($commento_id);

            // Restituisci i voti, se esistono
            return $voti ? $voti : [];

        } catch (Exception $e) {
            // Registra l'errore nel log
            error_log("Errore nel recupero dei voti: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ottieni il conteggio dei "Mi Piace" e "Non Mi Piace" per un commento di una specifica escursione
     *
     * @param int $commento_id ID del commento
     * @param int $voto_type Tipo di voto (1 per Mi Piace, -1 per Non Mi Piace)
     * @return int Conteggio dei voti
     */
    public function getLikeDislikeCount($commento_id, $voto_type) {
        try {
            if ($commento_id <= 0 || ($voto_type !== 1 && $voto_type !== -1)) {
                throw new Exception("Parametri non validi.");
            }

            // Ottieni il conteggio dei voti dal modello
            $count = $this->votoModel->getLikeDislikeCount($commento_id, $voto_type);

            // Restituisci il conteggio
            return (int) $count;

        } catch (Exception $e) {
            error_log("Errore nel recupero del conteggio dei voti: " . $e->getMessage());
            return 0; // Se c'è un errore, restituisci 0
        }
    }

    /**
     * Ottieni il voto di un utente per un determinato commento
     *
     * @param int $user_id ID dell'utente
     * @param int $commento_id ID del commento
     * @return int|null Il voto dell'utente (1 o -1) oppure null se l'utente non ha votato
     */
    public function getVotoPerCommentoUtente($user_id, $commento_id) {
        try {
            // Verifica la validità degli ID
            if ($user_id <= 0 || $commento_id <= 0) {
                throw new Exception("ID dell'utente o del commento non validi.");
            }

            // Ottieni il voto per il commento dall'utente
            $voto = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);

            return $voto !== null ? $voto : null;

        } catch (Exception $e) {
            // Registra l'errore nel log
            error_log("Errore nel recupero del voto per il commento: " . $e->getMessage());
            return null;
        }
    }
}
?>
