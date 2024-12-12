<?php 
    class Studente {
        public $anno;
        public $nome;
        public $cognome;

        public function NomeStudente() {
            return "Anno: " . $this->anno . "Nome: " . $this->nome . "Cognome: " . $this->cognome;
        }
    }
    
    $studente = new Studente();
    $studente -> anno = "Quinto</br>";
    $studente -> nome = "Riccardo</br>";
    $studente -> cognome = "Mestre";

    echo  $studente->NomeStudente();

?>