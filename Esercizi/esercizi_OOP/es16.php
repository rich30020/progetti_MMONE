<?php
    //Scrivi una classe PHP chiamata 'Calculator' che abbia una proprietà privata chiamata 'result'. Implementa metodi per eseguire operazioni aritmetiche di base come addizione e sottrazione

    class Calculator {
        private $result;

        public function __construct() {
            $result = 0;
        }

        public function getResult() {
            return $this->result;
        }

        public function add($number) {
            $this->result += $number;
        }

        public function subtract($number) {
            $this->result -= $number;
        }
    }
    
    $calculator = new Calculator();

    $calculator->add(5);
    $calculator->add(5);
    $calculator->subtract(3);

    $result = $calculator->getResult();
    echo "Il risultato è " . $result;
    
    
?>