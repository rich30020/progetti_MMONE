<?php
    abstract class Veicolo {
        abstract public function accellera();
    }

    class Automobile extends Veicolo {
        public function accellera() {
            return "L'automobile sta accelerando...";
        }
    }
    $automobile = new Automobile();
    echo $automobile->accellera();
?>
