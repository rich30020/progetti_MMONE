<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officina</title>
</head>
<body>
    
<?php
    class Auto {
        public $marca;
        public $colore;
        public function Officina(): string {
            return "marca: ". $this->marca . "colore: " . $this->colore;
        }    
    }

    $Auto = new Auto();
    $Auto -> marca = "Ferrari</br>";
    $Auto -> colore = "Rossa</br>";
    
    $Auto2 = new Auto();
    $Auto2 -> marca = "Nissan</br>";
    $Auto2 -> colore = "Grigia";
    

    echo $Auto->Officina();?>
    <hr>
    <?php
    echo $Auto2->Officina();

?>
</body>
</html>