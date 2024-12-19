<?php 
    interface Database {
        public function connetti();
        public function eseguiQuery($query);
        public function chiudiConnesione();
    }

    class MySQLDatabase implements Database {
        private $connessione;

        public function connetti() {
            $host = "127.0.0.1";
            $user = "root";
            $password = "";
            $database = "sistema_log";

            $this->connessione = new mysqli($host, $user, $password, $database);

            if ($this->connessione -> connect_error) {
                die("Errore di connessione" .$this->connessione->connect_error);
            }
        }
        public function eseguiQuery($query) {
            $result = $this->connessione->query($query);

            if ($result === false) {
                die("Errore nella query" .$this->connessione->connect_error);
            }

            while ($row = $result->fetch_assoc()) {
                print_r($row);
            }
        }

        public function chiudiConnesione() {
            $this->connessione->close();
        }   
    }

    $database = New MySQLDatabase();
    $database->connetti();
    $database->eseguiQuery("SELECT * FROM utenti");
    $database->chiudiConnesione();
?>