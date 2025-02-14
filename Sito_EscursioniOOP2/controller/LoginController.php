<?php
require_once __DIR__ . '/../Model/Utente.php';

class LoginController {
    public function login($email, $password) {
        $utenteModel = new Utente();
        $utente = $utenteModel->verificaCredenziali($email);

        if ($utente && password_verify($password, $utente['password'])) {
            $this->iniziaSessione($utente);
            header('Location: index.php');
            exit();
        } else {
            return "Credenziali errate!";
        }
    }

    private function iniziaSessione($utente) {
        session_start();
        // Imposta i parametri dei cookie di sessione per la durata di 30 giorni
        session_set_cookie_params([
            'lifetime' => 60 * 60 * 24 * 30,
            'path' => '/',
            'domain' => '',
            'secure' => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_regenerate_id(true); // Rigenera l'ID di sessione per sicurezza

        $_SESSION['user'] = [
            'id' => $utente['id'],
            'nome' => $utente['nome'],
            'email' => $utente['email']
        ];
        $_SESSION['autenticato'] = true;
    }

    public function checkAutenticazione() {
        session_start();
        return isset($_SESSION['autenticato']) && $_SESSION['autenticato'] === true;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit();
    }
}
?>
