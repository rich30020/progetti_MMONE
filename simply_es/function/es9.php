<?php 
    function RemoveSpace($string) {
        return str_replace(" ", "", $string);
    }

    $string = "Ciao sono Riccardo ho 18 anni e gioco a calcio";
    $stringWithoutSpacing = RemoveSpace($string);
    echo "La stringa senza spazi è: " .$stringWithoutSpacing;
?>