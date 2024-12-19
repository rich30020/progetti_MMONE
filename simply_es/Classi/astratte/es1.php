<?php 
    abstract class FiguraGeometrica {
        abstract public function calcolaArea();
    }

    class Quadrato extends FiguraGeometrica {
        private $lato;
         public function __construct($lato) {
            $this->lato = $lato;
         }
        
        public function calcolaArea() {
            return $this->lato * $this->lato;
        }
    }

    $quadrato = new Quadrato(10);
    echo "Area dal quadrato: " .$quadrato->calcolaArea();
?>