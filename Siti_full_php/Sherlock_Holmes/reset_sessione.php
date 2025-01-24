<?php

session_start();


$giocatoreId = 1;


include 'connessione.php';

// Funzione per resettare i punti del giocatore
function resetPunti($conn, $giocatoreId) {
    $stmt = $conn->prepare("UPDATE giocatori SET punti = 0 WHERE giocatore_id = ?");
    $stmt->bind_param("i", $giocatoreId);
    $stmt->execute();
    $stmt->close();
}


resetPunti($conn, $giocatoreId);
session_destroy();

header('Location: index.php');
exit();
?>
