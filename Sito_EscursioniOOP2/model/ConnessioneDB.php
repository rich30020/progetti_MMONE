<?php
class ConnessioneDB {

private static $instance = null;
private $conn;

// Costruttore privato per impedire la creazione di istanze
private function __construct() {
    $servername = "localhost";  // Cambia con il tuo host
    $username = "root";         // Cambia con il tuo username
    $password = "";             // Cambia con la tua password
    $dbname = "escursioni_db";  // Cambia con il nome del tuo database

    // Crea la connessione PDO
    try {
        $this->conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

// Restituisce l'istanza della connessione (Singleton pattern)
public static function getInstance() {
    if (self::$instance == null) {
        self::$instance = new ConnessioneDB();
    }
    return self::$instance->conn;  // Restituisce la connessione PDO
}

private function __clone() {}
public function __wakeup() {
    throw new Exception("Cannot unserialize a singleton.");
}
}
?>