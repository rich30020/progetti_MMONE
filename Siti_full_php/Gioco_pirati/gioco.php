<?php
session_start();
include('connessione.php');  

// Recupera i dettagli del giocatore
$query = "SELECT * FROM giocatori WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();
$giocatore = $result->fetch_assoc();

if (!$giocatore) {
    die("Errore: Dettagli del giocatore non trovati!");
}

$salute = $giocatore['salute'];
$saldo = $giocatore['saldo'];
$palle = $giocatore['palle'];
$livello_nave = $giocatore['livello_nave'];
$messaggio = "";

// Ottieni i dettagli della nave nemica dal database
$query = "SELECT * FROM navi_nemiche WHERE livello = ? AND vive = TRUE";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $livello_nave);
$stmt->execute();
$result = $stmt->get_result();
$nave = $result->fetch_assoc();

if (!$nave) {
    die("Errore: Dettagli della nave nemica non trovati!");
}

// Esegui un attacco (semplice o complesso)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attacco = $_POST['attacco'];

    $danno = ($attacco == "semplice") ? 50 : 100; // Imposta i danni degli attacchi qui

    if (($attacco == "semplice" && $palle > 0) || ($attacco == "complesso" && $palle >= 5)) {
        $palle -= ($attacco == "semplice") ? 1 : 5;
        $probabilita = 0.5; // Probabilità di successo al 50%

        if (rand(1, 100) <= $probabilita * 100) {
            $nave['vita'] -= $danno;

            // Aggiorna la vita della nave nel database
            $stmt = $conn->prepare("UPDATE navi_nemiche SET vita = ? WHERE livello = ?");
            $stmt->bind_param("ii", $nave['vita'], $livello_nave);
            $stmt->execute();

            if ($nave['vita'] <= 0) {
                $saldo += $nave['vincita'];
                $messaggio = "Hai distrutto la nave nemica e guadagnato €" . $nave['vincita'] . "!";
                $stmt = $conn->prepare("UPDATE navi_nemiche SET vive = FALSE WHERE livello = ?");
                $stmt->bind_param("i", $livello_nave);
                $stmt->execute();
                $livello_nave++;
                if ($livello_nave > 10) {
                    $livello_nave = 1;
                    $messaggio .= " Congratulazioni, hai vinto! Il gioco ricomincia.";
                }
                $palle = 10; // Reset delle palle di cannone a 10
                $salute = 300; // Reset della salute a 300
            } else {
                $messaggio = "Hai colpito la nave nemica! Vita rimanente: " . $nave['vita'];
            }
        } else {
            $messaggio = "L'attacco $attacco non ha avuto successo!";
            // Attacco del nemico solo se l'attacco del giocatore manca
            if (rand(1, 3) == 1) {
                $danno_nemico = $nave['danno'];
                $salute -= $danno_nemico;
                $messaggio .= "<br>La nave nemica ti ha colpito! Danno subito: $danno_nemico";
            }
        }
    } else {
        // Se si finiscono le palle di cannone... stampa $messaggio
        if ($livello_nave == 10 && $nave['vita'] > 0 && $palle <= 0) {
            $saldo = 0;
            header("Location: fine_gioco.html");
            exit();
        } else {
            $messaggio = "Non hai abbastanza palle di cannone per eseguire questo attacco.";
            header("Location: loosing.html");
            exit();
        }
    }

    // Salva i progressi
    $stmt = $conn->prepare("UPDATE giocatori SET salute = ?, saldo = ?, palle = ?, livello_nave = ? WHERE id = ?");
    $stmt->bind_param("iiiii", $salute, $saldo, $palle, $livello_nave, $_SESSION['id']);
    $stmt->execute();

    $_SESSION['saldo'] = $saldo;
    $_SESSION['salute'] = $salute;
    $_SESSION['livello_nave'] = $livello_nave;
    $_SESSION['messaggio'] = $messaggio;

    header("Location: index.php");
    exit();
}
?>
