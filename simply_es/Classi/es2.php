<?php

    class Prodotto {
        public $codice;
        public $nome;
        public $prezzo;

        public function Descrizione() {
            return "Codice: " . $this->codice . "Nome: " . $this->nome . "Prezzo: " . $this->prezzo;
        }
    }
        $prodotto = new Prodotto ();
        $prodotto -> codice = "17563 </br>";
        $prodotto -> nome = "Cuffie In-Ear </br>";
        $prodotto -> prezzo = 200;
    
    echo "Descrizione: " . $prodotto->Descrizione();

?>