<?php 
    //Write a PHP class called 'Student' with properties like 'name', 'age', and 'grade'. Implement a method to display student information.

    //Classe Student
    class Student {
        public $name;
        public $age;
        public $grade;

        function __construct($name, $age, $grade) {
            $this->name = $name;
            $this->age = $age;
            $this->grade = $grade;
        }
        public function Allievi() {
            echo "Nome: ". $this->name ."</br>";
            echo "Età: ". $this->age ."</br>";
            echo "Classe: ". $this->grade ."</br>";
        }
    }
    $Student = new Student("Riccardo", "18", "5");
    $Student2 = new Student("Leonardo", "15", "2");
    echo $Student->Allievi();?>
    <hr>
    <?php
    $Student2->Allievi();
?>