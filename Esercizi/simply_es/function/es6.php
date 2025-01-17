<?php
function BigArray($array) {
    $result = [];
    foreach ($array as $string) {
        $result[] = strtoupper($string);
    }
    return $result;
}

$string = ["ciao", "mondo"];
$result = BigArray($string);
echo "Le stringhe in maiuscolo sono: " . implode(", ", $result);
?>
