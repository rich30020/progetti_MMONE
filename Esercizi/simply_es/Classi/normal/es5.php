<?php
    class Cerchio {
        public $raggio;

        public $pigreco;

        public function __construct($raggio) {
            $this->raggio = $raggio;
            $this->pigreco = 3.14;
        }

        public function calcolaArea() {
            $raggiox2 = $this->raggio * $this->raggio;
            return $raggiox2 * $this->pigreco;
        }

        public function calcolaCirconferenza () {
            $raggioX2 = $this->raggio * 2;
            return $raggioX2 * $this->pigreco;
        }
    }

    $cerchio = new Cerchio( 5);
    echo "Area: " . $cerchio->calcolaArea();
    echo "Circonferenza: " . $cerchio->calcolaCirconferenza();

?>