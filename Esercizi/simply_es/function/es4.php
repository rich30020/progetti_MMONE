<?php 
    function ParioDispari($number) {
        if ($number % 2 == 0) {
            echo "True";
        } else {
            echo "False";
        }
    }

    $number = 5;
    echo ParioDispari($number);

?>