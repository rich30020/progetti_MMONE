<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema</title>
</head>
<body>
    
<?php
    class Film {
        public $titolo;
        public $regista;
        public $anno;
        public function Cinema(): string {
            return "Titolo: ". $this->titolo . "Regista: " . $this->regista . "Anno: " . $this->anno;
        }    
    }

    $Film = new Film();
    $Film -> titolo = "Quo Vado</br>";
    $Film -> regista = "Gennaro Nunziante</br>";
    $Film -> anno = "2016</br>";
    $Film2 = new Film();
    $Film2 -> titolo = "Nonno Scatenato</br>";
    $Film2 -> regista = "Dan Mazer";
    $Film2 -> anno = "2016</br>";

    echo $Film->Cinema();?>
    <hr>
    <?php
    echo $Film2->Cinema();

?>
</body>
</html>