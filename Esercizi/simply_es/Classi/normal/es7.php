<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoo</title>
</head>
<body>
    
<?php
    class Animale {
        public $nome;
        public $specie;

        public function Zoo(): string {
            return "Nome: ". $this->nome . "Specie: " . $this->specie;
        }    
    }

    $Libro = new Animale();
    $Libro -> nome = "Teddy</br>";
    $Libro -> specie = "Orsetto</br>";
    $Libro2 = new Animale();
    $Libro2 -> nome = "Tatiana</br>";
    $Libro2 -> specie = "Giraffa";

    echo $Libro->Zoo();?>
    <hr>
    <?php
    echo $Libro2->Zoo();

?>
</body>
</html>