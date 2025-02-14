<?php
class ConnessioneDB {
    private static $conn; // Variabile statica per la connessione

    // Metodo per ottenere una connessione al database
    public static function getInstance() {
        if (self::$conn === null) {
            // Parametri di connessione
            $host = 'localhost';    // Host del database
            $username = 'root';     // Username
            $password = '';         // Password
            $dbname = 'escursioni_db'; // Nome del database

            // Crea la connessione
            self::$conn = new mysqli($host, $username, $password, $dbname);

            // Controlla se la connessione è riuscita
            if (self::$conn->connect_error) {
                die("Connessione fallita: " . self::$conn->connect_error);
            }
        }
        return self::$conn; // Ritorna l'istanza della connessione
    }

    // Impedisce che venga clonata la connessione
    public function __clone() {}

    // Impedisce che venga serializzata la connessione
    public function __sleep() {}

    // Impedisce che venga deserializzata la connessione
    public function __wakeup() {}
}
?>
