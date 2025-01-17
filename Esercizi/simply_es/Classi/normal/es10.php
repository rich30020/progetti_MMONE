<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Negozio</title>
</head>
<body>
    
<?php
    class Prodotto {
        public $nome;
        public $prezzo;
        public function Negozio(): string {
            return "nome: ". $this->nome . "prezzo: " . $this->prezzo;
        }    
    }

    $Prodotto = new Prodotto();
    $Prodotto -> nome = "Mx Keys mini</br>";
    $Prodotto -> prezzo = "130€</br>";
    
    $Prodotto2 = new Prodotto();
    $Prodotto2 -> nome = "Materasso Memory Foam</br>";
    $Prodotto2 -> prezzo = "500€";
    

    echo $Prodotto->Negozio();?>
    <hr>
    <?php
    echo $Prodotto2->Negozio();

?>
</body>
</html>