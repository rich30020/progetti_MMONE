<!DOCTYPE html>
<html>
<body>

<?php
class Frutta {
  // Properties
  public $nome;
  public $colore;
  private $quantita;



  // Metodo
    function set_nome($nome) {
        $this->nome = $nome;
    }
    function get_nome() {
        return $this->nome;
    }

  // Metodo
    function set_colore($colore) {
        $this->colore = $colore;
    }
    function get_colore() {
        return $this->colore;
    }

    function set_quantita($quantita) {
        $this->quantita = $quantita;
    }

     function get_quantita() {
        return $this->quantita;
    }
}


$mela = new Frutta();
$mela->set_nome('Mela ');
$mela->set_colore('Verde');
$mela->set_quantita(' 5');

$banana = new Frutta();
$banana->set_nome('Banana ');
$banana->set_colore('Gialla');
$banana->set_quantita(' 15');
var_dump($mela instanceof Frutta);?><p>"Si l'oggetto mela fa parte della classe Frutta| instanceof |"</p>
<?php echo "<br>";

$mango = new Frutta();
$mango->set_nome("Mango ");
$mango->set_colore("Arancione");
$mango->set_quantita(' 3');



echo "Nome: " .$mango->get_nome() .$mango->get_colore() .$mango->get_quantita();      //contacatenato gli i valore dell'oggetto mango che fa parte della classe frutta
echo "<br>";    
echo "Nome: " .$mela->get_nome() .$mela->get_colore() .$mela->get_quantita();     //contacatenato gli i valore dell'oggetto mela che fa parte della classe frutta
echo "<br>";    
echo "Colore: " .$banana->get_nome() .$banana->get_colore() .$banana->get_quantita();  //contacatenato gli i valore dell'oggetto banana che fa parte della classe frutta
?>
 
</body>
</html>
