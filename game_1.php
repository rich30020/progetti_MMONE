<?php 
session_start(); 


// Funzione per ripristinare lo stato iniziale delle api
function resetGame() {
    $_SESSION['api'] = [
        "Ape Regina" => ["vita" => 100],  
        "Ape Operaia" => ["vita" => 75],  
        "Seconda Ape Operaia" => ["vita" => 75],
        "Terza Ape Operaia" => ["vita" => 75],
        "Quarta Ape Operaia" => ["vita" => 75],
        "Quinta Ape Operaia" => ["vita" => 75],
        "Ape Drone" => ["vita" => 50],    
        "Seconda Ape Drone" => ["vita" => 50],
        "Terza Ape Drone" => ["vita" => 50],
        "Quarta Ape Drone" => ["vita" => 50],
        "Quinta Ape Drone" => ["vita" => 50],
        "Sesta Ape Drone" => ["vita" => 50],
        "Settima Ape Drone" => ["vita" => 50],
        "Ottava Ape Drone" => ["vita" => 50],
    ];
}

// Quando premo il bottone reset 
if (isset($_POST['reset'])) {
    resetGame();
}


// Funzione che colpisce un'ape a caso
function scegliApe($api) {
   
    $apiVive = array_filter($api, function($ape) {
        return $ape['vita'] > 0;
    });

    
    if (empty($apiVive)) {
        echo "Tutte le api sono morte! Il gioco è finito.";
        return;
    }

    
    $apeCasuale = array_rand($apiVive);  
    $vitaApe = $apiVive[$apeCasuale]['vita']; 

    echo "Hai colpito la $apeCasuale che ha $vitaApe punti vita. Ora ne ha ";

    // Calcola il danno in base al tipo di ape
    if ($apeCasuale === 'Ape Regina') {
        $danno = 8;
        $api[$apeCasuale]['vita'] -= $danno; 
    } elseif (strpos($apeCasuale, 'Ape Operaia') !== false) {
        $danno = 10;
        $api[$apeCasuale]['vita'] -= $danno;
    } elseif (strpos($apeCasuale, 'Ape Drone') !== false) {
        $danno = 12;
        $api[$apeCasuale]['vita'] -= $danno;
    }

    $vitaRestante = $api[$apeCasuale]['vita'];
    echo "$vitaRestante punti vita.";

    // Verifico se è morta
    if ($vitaRestante <= 0) {
        echo " La $apeCasuale è morta!";
    }

    // Aggiorna la sessione con la nuova vita delle api
    $_SESSION['api'] = $api;
}

// se è stato compialto il POST del button parte la funzione scegliApe
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
    <!-- Inizia un ciclo per ciascun elemento nell'array api, il valore all'interno dell'array si "chiama" ape o dati -->
    <?php foreach ($_SESSION['api'] as $ape => $dati): ?>
        <!-- Scrivi nella lista ciascun ape mostrando il nome e i suoi hp -->
        <li><?php echo "$ape: " . $dati['vita'] . " HP"; ?></li>
    <?php endforeach; ?>
</ul>

</body>
</html>
