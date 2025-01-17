<?php
    abstract class Esercizio {
        abstract public function esegui();
    }

    class EsercizioMatematico extends Esercizio {
        public function esegui() {
            return "Sto esegundo l'esercizio Matematico";
        }
    }
    $EsercizioMatematico = new EsercizioMatematico();
    echo $EsercizioMatematico->esegui();
?>
