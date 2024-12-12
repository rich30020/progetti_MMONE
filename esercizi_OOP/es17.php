<?php
// Scrivi una classe PHP chiamata 'ShoppingCart' con proprietà come 'items' e 'total'. Implementa metodi per aggiungere articoli al carrello e calcolare il costo totale.

class ShoppingCart {
    public $items = [];
    public $total = 0;

    public function getTotal() {
        return $this->total;
    }

    public function addCart($item, $price) {
        $this->items[] = ['item' => $item, 'price' => $price];
        $this->total += $price;
    }

    public function displayCart() {
        foreach ($this->items as $item) {
            echo "Articolo: " . $item['item'] . " = Prezzo: " . $item['price'] . "</br>";
        }
        echo "Totale: " . $this->getTotal() . "</br>";
    }
}

$shoppingCart = new ShoppingCart();
$shoppingCart->addCart("Articolo1", 150);
$shoppingCart->addCart("Articolo2", 170);

$shoppingCart->displayCart();
?>
