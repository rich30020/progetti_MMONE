<?php
class ConnessioneDB {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "escursioni_db";
    public $conn;

    public function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connessione fallita: " . $this->conn->connect_error);
        }
    }
}

$db = new ConnessioneDB();
?>
