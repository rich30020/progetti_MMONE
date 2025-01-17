<?php
    //Scrivi una classe chiamata 'Prodotto' con proprietà come 'nome' e 'prezzo'. Implementa l'interfaccia 'Comparable' per confrontare i prodotti in base ai loro prezzi.

    interface Comparable {
        public function compareTo($other);
    }

    class Product implements Comparable {
        public $name;
        public $price;

        public function __construct($name, $price) {
            $this->name = $name;
            $this->price = $price;
        }

        public function getName() {
            return $this->name;
        }

        public function getPrice() {
            return $this->price;
        }

        public function compareTo($other) {
            if ($other instanceof Product) {
                if ($this->price < $other->getPrice()) {
                    return -1;
                } elseif ($this->price > $other->getPrice()) {
                    return 1;
                } else {
                    return 0;
                }
            } 
        }
    }
        $Product = new Product("Smartphone",300);
        $Product2 = new Product("Tablet",200);

        $result = $Product ->compareTo($Product2);

        if ($result < 0) {
            echo "Lo " .$Product->getName() . " è più economico del " . $Product2->getName() . "</br>";
        } elseif ($result > 0) {
            echo "Lo " .$Product->getName() . " è più caro del " . $Product2->getName() . "</br>";
        } else {
            echo "Lo" .$Product->getName() . " e " . "il " . $Product2->getName() . " hanno lo stesso prezzo.</br>";
        }

?>