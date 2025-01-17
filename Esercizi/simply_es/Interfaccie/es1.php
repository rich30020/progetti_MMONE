<?php 
    interface FormaGeometrica {
        public function calcolaArea();
    }

    class Quadrato implements FormaGeometrica {

        private $lato;
        public function __construct($lato) {
            $this->lato = $lato;
        }

        public function calcolaArea() {
            return $this->lato * $this->lato;
        }
    }

    $quadrato = new Quadrato(10);
    echo "L'area del quadrato è: " . $quadrato->calcolaArea();

?>