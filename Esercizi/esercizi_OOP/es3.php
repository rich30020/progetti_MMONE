<?php
    //Write a PHP class 'Rectangle' that has properties for length and width. Implement methods to calculate the rectangle's area and perimeter.
    
    // Classe astratta Rectangle
    abstract class Rectangle {
        public $length;
        public $width;

        public function __construct($width, $length) {
            $this->width = $width;
            $this->length = $length;
        }

        abstract public function getArea();
    }

    // Classe concreta che estende Rectangle
    class ConcreteRectangle extends Rectangle {
        public function getArea() {
            $rectangleArea = $this->length * $this->width;
            return $rectangleArea;
        }

        //per calcolare il perimetro
        public function getPerimeter() {
            $sommaB_H = $this->length + $this->width;
            $rectanglePerimeter = $sommaB_H * 2;
            return $rectanglePerimeter;
        }
    }

        $rectangle = new ConcreteRectangle(10, 5);
        echo "Area: " . $rectangle->getArea() . "</br>";
        echo "Perimetro: " . $rectangle->getPerimeter() . "</br>";
?>

