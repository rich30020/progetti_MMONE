<?php
    class Veicolo {
        public $marca;
        public $anno;

        public function Dettagli() {
            return "Marca: " . $this->marca . "Anno: " . $this->anno;
        }
    }

    $veicolo = new Veicolo();
    $veicolo -> marca = "Toyota Yaris, ";
    $veicolo -> anno = "2004</br>";

    echo "Dettagli Macchina: " . $veicolo->Dettagli();
?>