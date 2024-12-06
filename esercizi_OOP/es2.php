<?php 
    //  Write a PHP class called 'Circle' that has a radius property. Implement methods to calculate the circle's area and circumference.
    class Circle {
        public $radius;
        public $pigreco;


        public function __construct($radius) {
            $this->radius = $radius;
            $this->pigreco = 3.14;
    }

    public function getArea () {
        $radiusx2 = $this->radius * $this->radius;
        return $radiusx2 * $this->pigreco;
    }

    public function getCirconference () {
        $radiusper2 = $this->radius * 2;
        return $radiusper2 * $this->pigreco;
    }

}

    $circle = new Circle(25);
    echo "Area: " . $circle->getArea() . "</br>";
    echo "Circonferenza: " .$circle->getCirconference() . "</br>"
    
?>
