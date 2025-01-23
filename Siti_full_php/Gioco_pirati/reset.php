<?php
session_start();
session_destroy(); 

include('connessione.php');  

// Avvia una nuova sessione
session_start();
$_SESSION['salute'] = 300;
$_SESSION['saldo'] = 0;
$_SESSION['palle'] = 20;
$_SESSION['livello_nave'] = 1;
$_SESSION['messaggio'] = "La tua avventura pirata è stata resettata!";

// Elimina tutti i giocatori e reimposta le navi nemiche nel database
$query = "TRUNCATE TABLE giocatori";
$conn->query($query);

$query = "UPDATE navi_nemiche SET vive = TRUE, vita = CASE
    WHEN livello = 1 THEN 50
    WHEN livello = 2 THEN 100
    WHEN livello = 3 THEN 150
    WHEN livello = 4 THEN 200
    WHEN livello = 5 THEN 250
    WHEN livello = 6 THEN 300
    WHEN livello = 7 THEN 350
    WHEN livello = 8 THEN 400
    WHEN livello = 9 THEN 450
    WHEN livello = 10 THEN 500
    ELSE vita
END";
$stmt = $conn->prepare($query);
$stmt->execute();

header("Location: index.php");
exit();
?>
