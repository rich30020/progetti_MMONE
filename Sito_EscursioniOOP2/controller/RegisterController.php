<?php
require_once __DIR__ . '/../Model/Utente.php';

class RegisterController {

    public function register($nome, $email, $password, $eta, $livello_esperienza) {
        try {
            // Crea un'istanza del modello Utente
            $utenteModel = new Utente();

            // Verifica se l'email è già registrata
            if ($utenteModel->emailEsistente($email)) {
                return "Questa email è già registrata!";
            }

            // Crea un nuovo utente
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash della password
            $utenteModel->creaUtente($nome, $email, $hashedPassword, $eta, $livello_esperienza);
            return "Registrazione riuscita!";
        } catch (Exception $e) {
            error_log("Errore durante la registrazione: " . $e->getMessage());
            return "Si è verificato un errore durante la registrazione.";
        }
    }
}
?>
