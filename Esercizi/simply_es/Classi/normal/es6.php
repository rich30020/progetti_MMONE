<?php
    class Libro {
        public $titolo;
        public $autore;

        public function Libreria(): string {
            return "Titolo: ". $this->titolo . "Autore: " . $this->autore;
        }    
    }

    $Libro = new Libro();
    $Libro -> titolo = "La Storia di Ferrari</br>";
    $Libro -> autore = "Enzo Ferrari";

    echo $Libro->Libreria();

?>