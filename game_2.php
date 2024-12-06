<?php 
session_start();

if (!isset($_SESSION['api'])) {
    resetGame();
}

function resetGame() {
    $_SESSION['api'] = [
        'Queen' => [
            'nome' => 'Ape Regina',
            'vita' => 100,
            'danno' => 8
        ],
        'Operaie1' => [
            'nome' => 'Ape Operaia1',
            'vita' => 75,
            'danno' => 10
        ],
        'Operaie2' => [
            'nome' => 'Ape Operaia2',
            'vita' => 75,
            'danno' => 10
        ],
        'Operaie3' => [
            'nome' => 'Ape Operaia3',
            'vita' => 75,
            'danno' => 10
        ],
        'Operaie4' => [
            'nome' => 'Ape Operaia4',
            'vita' => 75,
            'danno' => 10
        ],
        'Operaie5' => [
            'nome' => 'Ape Operaia5',
            'vita' => 75,
            'danno' => 10
        ],
        'Drone1' => [
            'nome' => 'Ape Drone1',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone2' => [
            'nome' => 'Ape Drone2',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone3' => [
            'nome' => 'Ape Drone3',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone4' => [
            'nome' => 'Ape Drone4',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone5' => [
            'nome' => 'Ape Drone5',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone6' => [
            'nome' => 'Ape Drone6',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone7' => [
            'nome' => 'Ape Drone7',
            'vita' => 50,
            'danno' => 12
        ],
        'Drone8' => [
            'nome' => 'Ape Drone8',
            'vita' => 50,
            'danno' => 12
        ],
    ];
}

// Quando premo il bottone reset 
if (isset($_POST['reset'])) {
    resetGame();
}

// Funzione che colpisce un'ape a caso
function scegliApe($api) {

    $apiVive = array_filter($api, function ($ape) {
        return $ape['vita'] > 0;
    });

    if (empty($apiVive)) {
        echo "Tutte le api sono morte! Il gioco è finito.";
        return;
    }

    $indiceApeCasuale = array_rand($apiVive);
    $apeCasuale = $apiVive[$indiceApeCasuale]; //così facendo prende tutto l'elemento e non solo l'indice 
    $vitaApe = $apeCasuale['vita'];
    $danno = $apeCasuale['danno'];

    echo "Hai colpito la {$apeCasuale['nome']} che ha $vitaApe punti vita. Ora ha ";

    $api[$indiceApeCasuale]['vita'] -= $danno; //array => elemento estratto => vita - danno 

    $vitaRestante = $api[$indiceApeCasuale]['vita'];
    echo "$vitaRestante punti vita.";

    // Verifico se è morta
    if ($vitaRestante <= 0) {
        echo " L' {$apeCasuale['nome']} è morta!";
    }

    foreach ($api as $ape) {
        if ($ape['nome'] == 'Ape Regina' && $ape['vita'] <= 0) {
            echo " Il gioco è finito.";
            return;
        }
    }

    // Aggiorna la sessione con la nuova vita delle api
    $_SESSION['api'] = $api;
}

// se è stato compilato il POST del button parte la funzione scegliApe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['reset'])) {
    scegliApe($_SESSION['api']);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco delle Api</title>
</head>
<body>
    <h1>Gioco delle Api</h1>
    <p>Premi il bottone per colpire un'ape a caso! Ogni tipo di ape ha un danno diverso.</p>
    
    <!-- Form per premere il pulsante e colpire un'ape -->
    <form method="post">
        <button type="submit" style="width: 150px; height: 50px">Colpisci un'ape!</button>
    </form>

    <p>Premi il bottone per ripristinare tutte le vite delle api.</p>
    <form method="post">
        <button type="submit" name="reset" style="width: 150px; height: 50px">Resetta il gioco</button>
    </form>

    <h3>Stato delle api:</h3>
    <ul>
    <?php foreach ($_SESSION['api'] as $ape): ?>
        <li><?php echo "{$ape['nome']}: {$ape['vita']} HP"; ?></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>
