<?php
include('connessione.php'); 


session_start();

if (!isset($_SESSION['id'])) {
    // Crea un nuovo giocatore nel database e imposta l'ID della sessione
    $stmt = $conn->prepare("INSERT INTO giocatori (salute, saldo, palle, livello_nave) VALUES (300, 0, 10, 1)");
    $stmt->execute();
    $_SESSION['id'] = $conn->insert_id;
    $_SESSION['messaggio'] = "Benvenuto! Inizia la tua avventura pirata!";
}

$id = $_SESSION['id'];
$query = "SELECT * FROM giocatori WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$giocatore = $result->fetch_assoc();

$salute = $giocatore['salute'];
$saldo = $giocatore['saldo'];
$palle = $giocatore['palle'];
$livello_nave = $giocatore['livello_nave'];
$messaggio = $_SESSION['messaggio'];

// Recupera i dettagli della nave nemica dal database
$query = "SELECT * FROM navi_nemiche WHERE livello = ? AND vive = TRUE";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $livello_nave);
$stmt->execute();
$result = $stmt->get_result();
$nave_nemica = $result->fetch_assoc();

if (!$nave_nemica) {
    $nave_nemica = [
        'vita' => 0,
        'danno' => 0,
        'vincita' => 0
    ];
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gioco Pirata</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Gioco Pirata</h1>
    </header>

    <div id="gioco">
        <div class="stats">
            <p>Salute: <span id="salute"><?php echo $salute; ?></span></p>
            <p>Saldo: <span id="saldo"><?php echo $saldo; ?></span>€</p>
            <p>Palle di cannone: <span id="palle"><?php echo $palle; ?></span></p>
        </div>

        
        <div id="messaggio">
            <p><?php echo $messaggio; ?></p>
        </div>

        <!-- Dettagli della nave nemica -->
        <div id="naveNemica">
            <h3>Nave Nemica (Livello <?php echo $livello_nave; ?>)</h3>
            <p>Vita: <?php echo $nave_nemica['vita']; ?></p>
            <p>Danno: <?php echo $nave_nemica['danno']; ?></p>
            <p>Vincita: <?php echo $nave_nemica['vincita']; ?>€</p>
        </div>

        
        <div id="battaglia">
            <div id="oceano">
                
                <svg id="onde" viewBox="0 0 1440 320" preserveAspectRatio="none" height="5000px">
                    <path fill="#0099ff" fill-opacity="1" d="M0,32L30,42.7C60,53,120,75,180,101.3C240,128,300,160,360,176C420,192,480,192,540,186.7C600,181,660,171,720,170.7C780,171,840,181,900,170.7C960,160,1020,128,1080,117.3C1140,107,1200,117,1260,122.7C1320,128,1380,128,1410,128L1440,128L1440,320L1410,320C1380,320,1320,320,1260,320C1200,320,1140,320,1080,320C1020,320,960,320,900,320C840,320,780,320,720,320C660,320,600,320,540,320C480,320,420,320,360,320C300,320,240,320,180,320C120,320,60,320,30,320L0,320Z"></path>
                </svg>

                
                <svg id="ship" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
                    <image href="ship.svg" width="100" height="100" />
                </svg>

                
                <svg id="enemyShip" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" width="100" height="100">
                    <image href="ship.svg" width="100" height="100" />
                </svg>
            </div>
        </div>

        
        <div class="buttons-container">
            
            <form method="POST" action="gioco.php">
                <button type="submit" name="attacco" value="semplice">Attacco Semplice</button>
                <button type="submit" name="attacco" value="complesso">Attacco Complesso</button>
            </form>

            
            <form method="POST" action="reset.php">
                <button type="submit">Reset</button>
            </form>
        </div>
    </div>
</body>
</html>
