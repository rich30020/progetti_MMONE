<?php
    //Scrivi una classe chiamata 'Math' con metodi statici come 'add()', 'subtract()' e 'multiply()'. Usa questi metodi per eseguire calcoli matematici.

    class Math {
        public static function add($number1, $number2) {
            return $number1 + $number2;
        }

        public static function subtract($number1, $number2) {
            return $number1 - $number2;
        }

        public static function multiply($number1, $number2) {
            return $number1 * $number2;
        }
    }

    $number1 = 10; 
    $number2 = 10;

    $result1 = Math::add($number1, $number2);
    $result2 = Math::subtract($number1, $number2);
    $result3 = Math::multiply($number1, $number2);

    echo "L addizione di " . $number1 . " e ". $number2 . " è uguale a ". $result1 . "</br>";
    echo "La Divisione di " . $number1 . " e ". $number2 . " è uguale a ". $result2 . "</br>";
    echo "La moltiplicazione di " . $number1 . " e ". $number2 . " è uguale a ". $result3 . "</br>" ;

?>