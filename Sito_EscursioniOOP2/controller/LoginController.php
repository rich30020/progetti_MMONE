<?php
require_once __DIR__ . '/../Model/Utente.php';

class LoginController {

    public function login($email, $password) {
        $utenteModel = new Utente();
        $utente = $utenteModel->verificaCredenziali($email, $password);

        if ($utente) {
            session_start();
            $_SESSION['utente_id'] = $utente['id'];
            $_SESSION['nome'] = $utente['nome'];
            $_SESSION['email'] = $utente['email'];
            header('Location: index.php');
            exit();
        } else {
            return "Credenziali errate!";
        }
    }
}
?>
