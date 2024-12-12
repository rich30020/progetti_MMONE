<?php
    $number = 5;
    function fact($number) {
        if($number <=1 ) {
            return 1;
        } else {
            return $number * fact($number - 1);
        }
    }

    $result = fact($number);
    echo "Il fattoriale di " . $number . " è: " . $result;
?>