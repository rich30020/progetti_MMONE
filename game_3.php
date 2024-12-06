<?php
session_start();

if (!isset($_SESSION['api'])) {
    resetGame();
}

function resetGame() {
    $_SESSION['api'] = [
        [
            'tipo' => 'Queen',
            'nome' => 'Ape Regina',
            'vita' => 100,
            'danno' => 8,
        ],
        [   
            'tipo' => 'Operaia',
            'nome' => 'Ape Operaia',
            'vita' => 75, 
            'danno' => 10,
        ],
        [   
            'tipo' => 'Operaia',
            'nome' => 'Ape Operaia 1',
            'vita' => 75, 
            'danno' => 10,
        ],
        [   
            'tipo' => 'Operaia',
            'nome' => 'Ape Operaia 2',
            'vita' => 75, 
            'danno' => 10,
        ],
        [   
            'tipo' => 'Operaia',
            'nome' => 'Ape Operaia 3',
            'vita' => 75, 
            'danno' => 10,
        ],
        [   
            'tipo' => 'Operaia',
            'nome' => 'Ape Operaia 4',
            'vita' => 75, 
            'danno' => 10,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 2',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 3',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 4',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 5',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 6',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 7',
            'vita' => 50, 
            'danno' => 12,
        ],
        [   
            'tipo' => 'Drone',
            'nome' => 'Ape Drone 8',
            'vita' => 50, 
            'danno' => 12,
        ],
    ];
}

if (isset($_POST['reset'])) {  
    resetGame();
}

function scegliApe($api, $ApeSelezionata) {     
    
    $apeSelezionata = $api[$ApeSelezionata];    
    $vitaApe = $apeSelezionata['vita']; 
    $danno = $apeSelezionata['danno'];  


    echo "Hai colpito l' {$apeSelezionata['nome']} che ha $vitaApe punti vita. Ora ha ";

    $api[$ApeSelezionata]['vita'] -= $danno; 

    $vitaRestante = $api[$ApeSelezionata]['vita'];
    echo "$vitaRestante punti vita.";

    if ($vitaRestante <= 0) {
        echo " L' {$apeSelezionata['nome']} è morta!";
    }

    foreach ($api as $ape) {
        if ($ape['tipo'] == 'Queen' && $ape['vita'] <= 0) {
            echo " Il gioco è finito.";
            return;
        }
    }

    $_SESSION['api'] = $api;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset'])) {
    scegliApe($_SESSION['api'], $_POST['ape']);
}
$api = $_SESSION['api'];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco delle Api</title>
    <style>
        button:hover {
            background-color: #2980b9;
        }   
        body {
            background-color: #95a5a6
        }
        select {
            font-family: cursive;
        }
    </style>
</head>
<body>
    <h1 style="color: #f1c40f; font-family: cursive">Gioco delle Api</h1>
    <p style="font-family: cursive;">Seleziona l'ape da colpire e premi il bottone!</p>
    
    <form method="post">
        <select name="ape" id="ape" style="width: 200px; height: 30px; font-family: cursive;">
            <?php

            foreach ($api as $index => $ape) {
                ?>
                <option value="<?php echo $index;?>">
                    <?php 
                        echo $ape['nome'] ." (" .$ape['vita'].")";
                    ?>
                </option>
                <?php
            }

            ?>
        </select>
        <button id="bottone1" type="submit" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Colpisci un'ape!</button>
    </form>

    <p style="font-family: cursive;">Premi il bottone per ripristinare tutte le vite delle api.</p>
    <form method="post">
        <button id="bottone2" type="submit" name="reset" style="width: 150px; height: 50px; border-radius: 30px; font-family: cursive">Resetta il gioco</button>
    </form>

    <h3 style="color: #f1c40f; font-family: cursive">Stato delle api:</h3>
    <ul>
        <?php foreach ($api as $ape): ?>
            <li><?php echo "{$ape['nome']}: {$ape['vita']} HP"; ?>
            <img src='images/gif_ape.gif' style='width:50px;height:auto;'>
        <?php endforeach; ?>
    </ul>
</body>
</html>