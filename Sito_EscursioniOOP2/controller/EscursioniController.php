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
        $this->escursioneModel = new Escursione();
        $this->utenteModel = new Utente();
        $this->votoModel = new Voto();
    }
    public function getEscursioni() {
        return $this->escursioneModel->getAllEscursioni();
    }

    public function getEscursioneById($id) {
        return $this->escursioneModel->getEscursioneById($id);
    }

    public function aggiungiEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto) {
        return $this->escursioneModel->insertEscursione($userId, $sentiero, $durata, $difficolta, $punti, $foto);
    }

    public function getLikeDislikeCount($commento_id, $voto_type) {
        return $this->votoModel->getLikeDislikeCount($commento_id, $voto_type);
    }

    public function getUserById($userId) {
        return $this->utenteModel->getUserById($userId);
    }

    public function gestisciMiPiace($commento_id, $user_id, $tipo_voto, $escursione_id) {
        try {

            $voto = $this->votoModel->getVotoPerCommentoUtente($user_id, $commento_id);

            if ($voto !== null) {
                if ($voto != $tipo_voto) {
                    return $this->votoModel->aggiornaVoto($user_id, $commento_id, $tipo_voto);
                }
                return false;
            } else {
                return $this->votoModel->aggiungiVoto($user_id, $commento_id, $tipo_voto, $escursione_id);
            }
        } catch (Exception $e) {
            error_log("Errore nella gestione del Mi Piace: " . $e->getMessage());
            return false;
        }
    }

    public function getLikeDislikeCountPerEscursione($escursione_id) {
        try {
            return [
                'mi_piace' => $this->votoModel->getLikeDislikeCount($escursione_id, 1),
                'non_mi_piace' => $this->votoModel->getLikeDislikeCount($escursione_id, 0)
            ];
        } catch (Exception $e) {
            error_log("Errore nel recupero dei conteggi Mi Piace/Non Mi Piace per escursione: " . $e->getMessage());
            return ['mi_piace' => 0, 'non_mi_piace' => 0]; 
        }
    }
}
?>
