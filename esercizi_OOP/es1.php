<?php
    // Write a PHP class 'Rectangle' that has properties for length and width. Implement methods to calculate the rectangle's area and perimeter.
class Rectangle {
    private $length;
    private $width;

    public function __construct($length, $width) {
        $this->length = $length;
        $this->width = $width;
    }

    public function getArea() {
        return $this->length * $this->width;
    }

    public function getPerimeter() {
        return 2 * ($this->length + $this->width);
    }
}

$rectangle = new Rectangle(12, 9);
echo "Area: " . $rectangle->getArea() . "</br>";
echo "Perimeter: " . $rectangle->getPerimeter() . "</br>";
?>