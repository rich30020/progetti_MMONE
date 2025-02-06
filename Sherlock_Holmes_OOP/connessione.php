<?php
// Classe per la connessione al database
class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    // Costruttore per inizializzare le variabili di connessione
    public function __construct($servername = "localhost", $username = "root", $password = "", $dbname = "gioco_sherlock_holmes_db") {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->conn = null;  // Connessione inizialmente nulla
    }

    // Metodo per avviare la connessione
    public function connetti() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Verifica connessione
        if ($this->conn->connect_error) {
            die("Connessione fallita: " . $this->conn->connect_error);
        }
    }

    // Metodo per ottenere la connessione
    public function getConnessione() {
        if ($this->conn === null) {
            $this->connetti();  // Connessione se non ancora attiva
        }
        return $this->conn;
    }

    // Metodo per chiudere la connessione
    public function chiudiConnessione() {
        if ($this->conn !== null) {
            $this->conn->close();
        }
    }
}

// Creazione dell'istanza e connessione
$database = new Database();
$database->connetti();

// Ottenere la connessione
$conn = $database->getConnessione();
?>
