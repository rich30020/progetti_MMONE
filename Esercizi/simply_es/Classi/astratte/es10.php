<?php
      abstract class Veicoli {
        abstract public function Ferma();
      }

      class Auto extends Veicoli {
        public function Ferma(){
            return "L'auto è rimasta ferma";
        }
    }
    $auto = new Auto();
    echo $auto->Ferma();
?>