<?php 
    //Scrivi una classe astratta PHP chiamata 'Animal' con metodi astratti come 'eat()' e 'makeSound()'. Crea sottoclassi come 'Dog', 'Cat' e 'Bird' che implementano questi metodi.


abstract class Animal
{
    abstract public function eat();
    abstract public function makeSound();
}

class Dog extends Animal
{
    public function eat()
    {
        echo "Il cane sta mangiando.</br>";
    }

    public function makeSound()
    {
        echo "Il cane sta abbaiando.</br>";
    }
}

class Cat extends Animal
{
    public function eat()
    {
        echo "Il gatto sta mangiando.</br>";
    }

    public function makeSound() {
        echo "Il gatto sta miagolando.</br>";
    }
}

class Bird extends Animal
 {
    public function eat()
    {
        echo "L'uccello sta mangiando.</br>";
    }

    public function makeSound()
    {
        echo "L'uccello sta cinguettando.</br>";
    }
}

$dog = new Dog();
$dog->eat();
$dog->makeSound();

$cat = new Cat();
$cat->eat();
$cat->makeSound();

$bird = new Bird();
$bird->eat();
$bird->makeSound();
?>
