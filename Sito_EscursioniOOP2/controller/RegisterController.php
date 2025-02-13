<?php
require_once __DIR__ . '/../Model/Utente.php';

class RegisterController {

    public function register($nome, $email, $password, $eta, $livello_esperienza) {
        // Crea un'istanza del modello Utente
        $utenteModel = new Utente();

        // Verifica se l'email è già registrata
        if ($utenteModel->emailEsistente($email)) {
            return "Questa email è già registrata!";
        }

        // Crea un nuovo utente
        $utenteModel->creaUtente($nome, $email, $password, $eta, $livello_esperienza);
        return "Registrazione riuscita!";
    }
}
?>
