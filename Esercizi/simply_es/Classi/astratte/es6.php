<?php
    abstract class Forma {
        abstract public function disegna();
    }

    class Cerchio extends Forma {
        public function disegna() {
            return "Sto disegnando un cerchio";
        }
    }
    $Cerchio = new Cerchio();
    echo $Cerchio->disegna();
?>
