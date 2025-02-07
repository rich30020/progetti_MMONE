<?php
// Inizia la sessione
session_start();

// Classe BeeType per gestire i tipi di api
class BeeType {
    public $type;
    public $name;
    public $health_point;
    public $hit_damage;

    public function __construct($type, $name, $health_point, $hit_damage) {
        $this->type = $type;
        $this->name = $name;
        $this->health_point = $health_point;
        $this->hit_damage = $hit_damage;
    }
}

// Classe Bee per gestire le api
class Bee {
    public $type;
    public $name;
    public $health_point;
    public $hit_damage;

    public function __construct($type, $name, $health_point, $hit_damage) {
        $this->type = $type;
        $this->name = $name;
        $this->health_point = $health_point;
        $this->hit_damage = $hit_damage;
    }

    public function hit() {
        $this->health_point -= $this->hit_damage;
        if ($this->health_point < 0) {
            $this->health_point = 0;
        }
    }
}

// Classe BeeGame per gestire il gioco delle api
class BeeGame {
    private $conn;
    private $beeTypes = [];
    public $bees = [];
    private $numberOfQueenBees = 1;
    private $numberOfDroneBees = 8;
    private $numberOfWorkerBees = 5;

    public function __construct($host, $user, $password, $database) {
        $this->conn = new mysqli($host, $user, $password, $database);
        if ($this->conn->connect_error) {
            die("Errore di connessione: " . $this->conn->connect_error);
        }
        $this->loadBeeTypes();
    }

    private function loadBeeTypes() {
        $sql = "SELECT * FROM beeType";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $this->beeTypes[] = new BeeType($row['type'], $row['name'], $row['health_point'], $row['hit_damage']);
            }
        }
    }

    public function findBeeType($type) {
        foreach ($this->beeTypes as $beeType) {
            if ($beeType->type == $type) {
                return $beeType;
            }
        }
        return null;
    }

    public function createBeeAve() {
        $this->bees = [];
        $this->addBeeToArray('queen_bee', $this->numberOfQueenBees);
        $this->addBeeToArray('worker_bee', $this->numberOfWorkerBees);
        $this->addBeeToArray('drone_bee', $this->numberOfDroneBees);
    }

    private function addBeeToArray($type, $number) {
        $beeType = $this->findBeeType($type);
        for ($i = 0; $i < $number; $i++) {
            if ($beeType) {
                $this->bees[] = new Bee($beeType->type, $beeType->name . " $i", $beeType->health_point, $beeType->hit_damage);
            }
        }
    }

    public function getBees() {
        return $this->bees;
    }

    public function hitBee($index) {
        if (isset($this->bees[$index])) {
            $this->bees[$index]->hit();
            $this->updateSession();
        }
    }

    public function resetGame() {
        $this->createBeeAve();
        $this->updateSession();
    }

    public function isQueenDead() {
        foreach ($this->bees as $bee) {
            if ($bee->type == 'queen_bee' && $bee->health_point == 0) {
                return true;
            }
        }
        return false;
    }

    private function updateSession() {
        $_SESSION['bees'] = serialize($this->bees);
    }
}

$game = new BeeGame("127.0.0.1", "root", "", "bees");

if (!isset($_SESSION['bees'])) {
    $game->resetGame();
} else {
    $bees = unserialize($_SESSION['bees']);
    $game->bees = $bees;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hit']) && isset($_POST['bee'])) {
        $selectedBeeIndex = $_POST['bee'];
        $game->hitBee($selectedBeeIndex);
    }

    if (isset($_POST['reset'])) {
        $game->resetGame();
    }
}

$bees = $game->getBees();
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/bee.png">
    <title>Gioco delle Api</title>
    <style>
        button:hover { background-color: #2980b9; }
        body { background-color: #95a5a6; }
        select { font-family: cursive; }
        .bee-container { display: flex; flex-wrap: wrap; }
        .bee { margin: 30px; text-align: center; }
    </style>
</head>
<body>
    <h1 style="color: #f1c40f; font-family: cursive">Gioco delle Api</h1>
    <p id="selectBee" style="font-family: cursive;">Seleziona l'ape da colpire e premi il bottone!</p>
    <form method="post">
        <select name="bee" id="bee" style="width: 200px; height: 30px; font-family: cursive;">
            <?php foreach ($bees as $index => $bee): ?>
                <option value="<?= $index; ?>" <?= ($_POST['bee'] ?? '') == $index ? 'selected' : '' ?>>
                    <?= $bee->name; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button id="bottone1" type="submit" name="hit" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Colpisci un'ape!</button>
    </form>

    <p style="font-family: cursive;">Premi il bottone per ripristinare tutte le vite delle api.</p>
    <form method="post">
        <button id="bottone2" type="submit" name="reset" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Resetta il gioco</button>
    </form>

    <h3 style="color: #f1c40f; font-family: cursive">Stato delle api:</h3>
    <div class="bee-container">
        <?php
        $isQueenDead = $game->isQueenDead();
        foreach ($bees as $bee): ?>
            <div class="bee">
                <?php if ($isQueenDead): ?>
                    <img src="images/dead_bee.gif" width="150px" height="150px">
                    <br><?= $bee->name; ?> è morta
                <?php else: ?>
                    <?php if ($bee->health_point == 0): ?>
                        <img src="images/dead_bee.gif" width="150px" height="150px">
                        <br>L'ape è morta
                    <?php else: ?>
                        <?php if ($bee->type == 'queen_bee'): ?>
                            <img src="images/queen_bee.gif" width="150px" height="150px">
                            <br><?= $bee->name; ?>
                            <br><?= $bee->health_point; ?> punti vita
                        <?php elseif ($bee->type == 'worker_bee'): ?>
                            <img src="images/worker_bee.gif" width="150px" height="150px">
                            <br><?= $bee->name; ?>
                            <br><?= $bee->health_point; ?> punti vita
                        <?php elseif ($bee->type == 'drone_bee'): ?>
                            <img src="images/drone_bee.gif" width="150px" height="150px">
                            <br><?= $bee->name; ?>
                            <br><?= $bee->health_point; ?> punti vita
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php 
        echo "<p style='color:#95a5a6'>" . json_encode($bees) . "</p>"
    ?>
</body>
</html>
