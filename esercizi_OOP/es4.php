<?php
    //Write a PHP interface called 'Resizable' with a method 'resize()'. Implement the 'Resizable' interface in a class called 'Square' and add functionality to resize the square.

    interface Resizable {
        public function resize($percentage);
    }

class Square implements Resizable {
        public $side;
    

    public function __construct($side) {
        $this->side = $side;
    }

    public function resize($percentage) {
        $this->side = $percentage /10;
    }    
    
    public function getArea() {
        $boxArea = $this->side * $this->side;
        return $boxArea;
    }

    public function getSide() {
        return $this->side;
    }
} 

    $Square = new Square(10);
    echo "Lunghezza lato, iniziale: ". $Square->getSide() ."</br>";
    $Square->resize(60);
    echo "Lunghezza lato, risezionata: ". $Square->getSide() ."</br>";

    echo "Area: ". $Square->getArea() ."</br>";


?>