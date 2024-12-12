<?php
// Scrivere una classe chiamata 'Employee' che estende la classe 'Person' e aggiunge proprietà come 'salary' e 'position'. Implementare metodi per visualizzare i dettagli dei dipendenti.

// Classe base Person
class Person {
    public $name;
    public $age;

    function __construct($name, $age) {
        $this->name = $name;
        $this->age = $age;
    }
}

// Classe Employee che estende Person
class Employee extends Person {
    public $salary;
    public $position;

    function __construct($salary, $position) {
        $this->salary = $salary;
        $this->position = $position;
    }

    public function writeEmployee() {
        echo "Posizione Lavorativa: " . $this->position . "</br>";
        echo "Salario: " . $this->salary . "</br>";
    }
}

// Creare istanze di Employee
$Employee = new Employee( "1500", "Dipendente d'ufficio");
$Employee2 = new Employee("5000", "Responsabile di produzione");
$Employee3 = new Employee( "15000", "Titolare");


$Employee->writeEmployee();
echo "<hr>";
$Employee2->writeEmployee();
echo "<hr>";
$Employee3->writeEmployee();
?>
