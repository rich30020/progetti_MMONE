<?php
    abstract class Persona {
        abstract public function saluta();
    }

    class Studente extends Persona {
        public function saluta() {
            return "Lo studente ha salutato";
        }
    }
    $Studente = new Studente();
    echo $Studente->saluta();
?>
