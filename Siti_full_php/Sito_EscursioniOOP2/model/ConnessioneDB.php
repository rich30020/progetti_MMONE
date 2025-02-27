<?php

class ConnessioneDB {
    protected $connessione;
    private static $instance = null;

    private function __construct() {
        $host = 'localhost';
        $user = 'root';
        $password = '';
        $db = 'escursioni_db';

        $this->connessione = new mysqli($host, $user, $password, $db);

        if ($this->connessione->connect_error) {
            error_log('Connessione fallita: ' . $this->connessione->connect_error);
            die('Errore di connessione al database.');
        }

        $this->connessione->set_charset('utf8mb4');
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new ConnessioneDB();
        }
        return self::$instance;
    }

    public function getConnessione() {
        return $this->connessione;
    }

    public function __destruct() {
        if ($this->connessione) {
            $this->connessione->close();
        }
    }
}

?>
