<?php
// Inizia la sessione
session_start(); 

// Connessione al database
$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "bees";

$connessione = new mysqli($host, $user, $password, $database);

if ($connessione->connect_error) {
    die("Errore di connessione: " . $connessione->connect_error);
}

// Funzione per recuperare i tipi di api dal database
function getBeeTypes($connessione) {
    $sql = "SELECT * FROM beeType";
    $result = $connessione->query($sql);
    $beeTypes = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $beeTypes[] = $row;
        }
    }
    #var_dump($beeTypes);
    return $beeTypes;
}

// Recupera i tipi di api dal database
$beeTypes = getBeeTypes($connessione);

$numberOfQueenBees = 1; 
$numberOfDroneBees = 8; 
$numberOfWorkerBees = 5;

// Trova il tipo di ape
function findBeeType($type) {
    global $beeTypes;
    foreach ($beeTypes as $value) {
        if ($value['type'] == $type) {
            return $value;
        }
    }
    return null;
}

// Aggiungi api all'array
function addBeeToArray($bees, $beeType, $numberOfBees) {
    for ($i = 0; $i < $numberOfBees; $i++) {
        if ($beeType) {
            $bees[] = [
                'type' => $beeType['type'],
                'name' => $beeType['name'] . " $i",
                'health_point' => $beeType['health_point'],
                'hit_damage' => $beeType['hit_damage']
            ];
        }
    }
    return $bees;
}

// Crea le api
function createBeeAve() {
    global $numberOfQueenBees, $numberOfDroneBees, $numberOfWorkerBees;
    $bees = [];
    $bees = addBeeToArray($bees, findBeeType('queen_bee'), $numberOfQueenBees);
    $bees = addBeeToArray($bees, findBeeType('worker_bee'), $numberOfWorkerBees);
    $bees = addBeeToArray($bees, findBeeType('drone_bee'), $numberOfDroneBees);
    return $bees;
}

// Inizializza l'array delle api nella sessione se non esiste
if (!isset($_SESSION['bees'])) {
    $_SESSION['bees'] = createBeeAve();
}
$bees = &$_SESSION['bees']; // Usa l'array delle api

// Gestisci pulsanti
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['hit'])) {
        $selectedBeeIndex = $_POST['bee'];
        if (isset($bees[$selectedBeeIndex])) {
            $bees[$selectedBeeIndex]['health_point'] -= $bees[$selectedBeeIndex]['hit_damage'];
            if ($bees[$selectedBeeIndex]['health_point'] < 0) {
                $bees[$selectedBeeIndex]['health_point'] = 0;
            }
        }
    }

    if (isset($_POST['reset'])) {
        $_SESSION['bees'] = createBeeAve();
        $bees = &$_SESSION['bees'];
    }
}
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
                    <?= $bee['name']; ?>
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
        $isQueenDead = false;
        foreach ($bees as $bee) {
            if ($bee['type'] == 'queen_bee' && $bee['health_point'] == 0) {
                $isQueenDead = true;
                break;
            }
        }
        ?>
        
        <?php foreach ($bees as $bee): ?>
            <div class="bee">
                <?php if ($isQueenDead): ?>
                    <img src="images/dead_bee.gif" width="150px" height="150px">
                    <br><?= $bee['name']; ?> è morta 
                <?php else: ?>
                    <?php if ($bee['health_point'] == 0): ?>
                        <img src="images/dead_bee.gif" width="150px" height="150px">
                        <br>L'ape è morta
                    <?php else: ?>
                        <?php if ($bee['type'] == 'queen_bee'): ?>
                            <img src="images/queen_bee.gif" width="150px" height="150px">
                            <br><?= $bee['name']; ?>
                            <br><?= $bee['health_point']; ?> punti vita
                        <?php elseif ($bee['type'] == 'worker_bee'): ?>
                            <img src="images/worker_bee.gif" width="150px" height="150px">
                            <br><?= $bee['name']; ?>
                            <br><?= $bee['health_point']; ?> punti vita
                        <?php elseif ($bee['type'] == 'drone_bee'): ?>
                            <img src="images/drone_bee.gif" width="150px" height="150px">
                            <br><?= $bee['name']; ?>
                            <br><?= $bee['health_point']; ?> punti vita
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
