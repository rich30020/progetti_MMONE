<?php
    class Contatore {
        static public $valore = 0;
         public static function incrementa() {
            self::$valore++;
         }
    }

    echo Contatore::$valore; // 0
    Contatore::incrementa();
    ?><br>
    <?php
    echo Contatore::$valore; // 1
    ?></br>
    <?php
    Contatore::incrementa(); 
    echo Contatore::$valore; //2
?>