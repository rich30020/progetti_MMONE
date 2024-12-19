<?php
    abstract class Veicolo {
        abstract public function avvia();
    }

    class moto extends Veicolo {
        public function avvia() {
            return "Sono riusciuto ad avviare la moto";
        }
    }
    $moto = new moto();
    echo $moto->avvia();
?>
