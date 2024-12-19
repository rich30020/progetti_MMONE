<?php
    abstract class Animale {
        abstract public function emettiVerso();
    }

    class Cane extends Animale {
        public function emettiVerso() {
            return "Bau Bau";
        }
    }
    $cane = new Cane();
    echo $cane->emettiVerso();
?>
