<?php
    //Write a PHP class called 'Vehicle' with properties like 'brand', 'model', and 'year'. Implement a method to display the vehicle details.

    //Classe veicolo
    class Vehicle {
        public $brand;
        public $model;
        public $year;

        function __construct($brand, $model, $year) {
            $this->brand = $brand;
            $this->model = $model;
            $this->year = $year;
        }

        public function feature() {
        echo "Brand: ". $this->brand. "</br>";
        echo "Model: ". $this->model. "</br>";
        echo "Year: ". $this->year. "</br>";
        }
    }
    $vehicle = new Vehicle("Nissan", "Qashqai", "2024");
    echo $vehicle->feature();
?>