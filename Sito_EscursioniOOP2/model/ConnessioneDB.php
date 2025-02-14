<?php

class ConnessioneDB {
    // La connessione al database
    protected $connessione;

    // Variabile statica per l'istanza unica (Singleton)
    private static $instance = null;

    // Costruttore privato per creare la connessione al database
    private function __construct() {
        // Parametri per la connessione al database
        $host = 'localhost';  // Modifica con il tuo host
        $user = 'root';       // Modifica con il tuo utente
        $password = '';       // Modifica con la tua password
        $db = 'escursioni_db'; // Modifica con il nome del tuo database

        // Crea la connessione
        $this->connessione = new mysqli($host, $user, $password, $db);

        // Controlla se la connessione ha avuto successo
        if ($this->connessione->connect_error) {
            // Usa una gestione dell'errore più elegante
            error_log('Connessione fallita: ' . $this->connessione->connect_error);
            die('Connessione fallita: ' . $this->connessione->connect_error);
        }

        // Impostazione del set di caratteri per evitare problemi con i caratteri speciali
        $this->connessione->set_charset('utf8mb4');
    }

    // Metodo per ottenere l'istanza unica della connessione (Singleton)
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ConnessioneDB();
        }
        return self::$instance;
    }

    // Funzione per ottenere la connessione al database
    public function getConnessione() {
        return $this->connessione;
    }

    // Metodo per eseguire una query SQL
    public function query($sql) {
        // Aggiungi un controllo per la validità della query
        $result = $this->connessione->query($sql);
        if ($this->connessione->error) {
            error_log("Errore nella query: " . $this->connessione->error);
        }
        return $result;
    }

    // Metodo per preparare una query SQL
    public function prepare($sql) {
        $stmt = $this->connessione->prepare($sql);
        if ($stmt === false) {
            error_log("Errore nella preparazione della query: " . $this->connessione->error);
        }
        return $stmt;
    }

    // Metodo per chiudere la connessione al database (opzionale)
    public function closeConnection() {
        if ($this->connessione) {
            $this->connessione->close();
        }
    }

    // Distruttore per chiudere la connessione automaticamente
    public function __destruct() {
        $this->closeConnection();
    }
}
?>
