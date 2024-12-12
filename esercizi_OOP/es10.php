<?php
    //Scrivi una classe PHP chiamata 'Persona' con proprietà come 'nome' ed 'età'. Implementa il metodo magico '__toString()' per visualizzare le informazioni sulla persona.

    class Person {
        public $name;

        public $age;


        function __construct($name, $age) {
            $this->name = $name;
            $this->age = $age;
        }

        public function WritePerson() {
            echo "Nome: " .$this->name . "</br>";
            echo "Età: " .$this->age . "</br>";
        }
    }
    $Person = new Person("Riccardo", "18");
    $Person2 = new Person("Fabio", "53");
    echo $Person->WritePerson();?>
    <hr>
    <?php
        echo $Person2->WritePerson();
    ?>