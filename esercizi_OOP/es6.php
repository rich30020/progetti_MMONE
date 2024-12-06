<?php 
// Scrivere una gerarchia di classi PHP per un sistema di biblioteca, inclusi classi come 'LibraryItem', 'Review', 'DVD', ecc. Implementare le proprietà e i metodi appropriati per ogni classe.

// Classe base LibraryItem
class LibraryItem {
    public $title;
    public $author;
    public $year;
    public $model;

    public function __construct($title, $author, $year, $model) {
        $this->title = $title;
        $this->author = $author;
        $this->year = $year;
        $this->model = $model;
    }

    public function displayInfo() {
        echo "Titolo: " . $this->title . "</br>";
        echo "Autore: " . $this->author . "</br>";
        echo "Anno: " . $this->year . "</br>";
        echo "Modello: " . $this->model . "</br>";
    }
}

    //Aggiungo una classe Review e la aggiungo alla classe inziale LibraryItem
    class Review extends LibraryItem {
        public $review;
    


    public function __construct($title, $author, $year, $model, $review) {
        $this->review = $review;
        parent::__construct($title, $author, $year, $model);
        $this->review = $review;
    }

    public function getReview() {
        return $this->review;
    }

    public function displayInfo() {
        echo "Titolo: " . $this->title . "</br>";
        echo "Autore: " . $this->author . "</br>";
        echo "Anno: " . $this->year . "</br>";
        echo "Modello: " . $this->model . "</br>";
        echo "Recensioni: " .$this->review . "</br>";
    }

}
// Esempio di utilizzo
$librayItem= new Review("Cronache del tempo Sospeso", "Anya Petrova", "2042", "Post-apocalittico", "Positive");
$librayItem->displayInfo();
?>
