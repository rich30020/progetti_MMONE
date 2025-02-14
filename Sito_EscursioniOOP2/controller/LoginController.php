<?php
require_once __DIR__ . '/../Model/Utente.php'; // Modifica l'inclusione per il modello Utente

class LoginController {

    public function login($email, $password) {
        // Crea un'istanza del modello Utente
        $utenteModel = new Utente();
        
        // Verifica le credenziali dell'utente nel database
        $utente = $utenteModel->verificaCredenziali($email);

        if ($utente) {
            // Se l'utente esiste, verifica la password con password_verify
            if (password_verify($password, $utente['password'])) {
                // Inizia la sessione e memorizza i dati dell'utente
                $this->iniziaSessione($utente);

                // Reindirizza alla pagina principale (index.php)
                header('Location: index.php');
                exit();
            } else {
                // Credenziali errate
                return "Credenziali errate!";
            }
        } else {
            // Se l'utente non esiste, restituisci un errore
            return "Credenziali errate!";
        }
    }

    // Funzione per iniziare la sessione dell'utente
    private function iniziaSessione($utente) {
        session_start();  // Avvia la sessione

        // Imposta un cookie di sessione per un tempo di scadenza lungo (30 giorni)
        // Impostiamo la durata della sessione a 30 giorni (30 giorni * 24 ore * 60 minuti * 60 secondi)
        ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30);  // 30 giorni
        session_set_cookie_params(60 * 60 * 24 * 30); // Imposta i parametri del cookie di sessione (durata di 30 giorni)

        // Salva i dati dell'utente nella sessione
        $_SESSION['user'] = [
            'id' => $utente['id'],
            'nome' => $utente['nome'],
            'email' => $utente['email']
        ];
        $_SESSION['autenticato'] = true;
    }

    // Funzione per controllare se l'utente è già autenticato
    public function checkAutenticazione() {
        session_start(); // Avvia la sessione
        // Controlla se la sessione è ancora valida e se l'utente è autenticato
        if (isset($_SESSION['autenticato']) && $_SESSION['autenticato'] === true) {
            return true;
        }
        return false;
    }

    // Funzione per fare il logout
    public function logout() {
        session_start(); // Avvia la sessione
        // Rimuove tutte le variabili di sessione
        session_unset();
        
        // Distrugge la sessione
        session_destroy();
        
        // Reindirizza alla pagina di login
        header('Location: login.php');
        exit();
    }
}
?>
