<?php 
    abstract class Prodotto {
        abstract public function calcolaPrezzo();
    }

    class Libro extends Prodotto
    {
        private $prezzo;

        public function __construct($prezzo) {
            $this->prezzo = $prezzo;
        }

        public function calcolaPrezzo() {
            return $this->prezzo;
        }
    }

    $libro = new Libro(50);
    echo "Prezzo del libro: " . $libro->calcolaPrezzo();
?>