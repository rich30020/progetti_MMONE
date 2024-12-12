<?php
    class Persona {
        public $nome;
        public $cognome;

        public function creaPersona() {
            return $this->nome . " " . $this->cognome;
        }
    }
        $persona = new Persona();
        $persona ->nome = "Riccardo";
        $persona ->cognome = "Mestre";
    
    echo "Nome Completo: " . $persona->creaPersona();
?>