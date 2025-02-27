<?php

class Database {
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $connessione;

    
    public function __construct($servername = "localhost", $username = "root", $password = "", $dbname = "gioco_sherlock_holmes_db") {
        $this->servername = $servername;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;
        $this->connessione = null;  
    }

    // Metodo per avviare la connessione
    public function connetti() {
        
        $this->connessione = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        // Verifica la connessione
        if ($this->connessione->connect_error) {
            
            die("Connessione fallita: " . $this->connessione->connect_error);
        }
    }

    // Metodo per ottenere la connessione
    public function getConnessione() {
        
        if ($this->connessione === null) {
            $this->connetti();  
        }
        return $this->connessione;
    }

    // Metodo per chiudere la connessione
    public function chiudiConnessione() {
        
        if ($this->connessione !== null) {
            $this->connessione->close();
        }
    }
}


$database = new Database();


try {
    $database->connetti();  
    $conn = $database->getConnessione(); 



} catch (Exception $e) {
    
    echo "Errore: " . $e->getMessage();
}
?>
