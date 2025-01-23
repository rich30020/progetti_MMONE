<?php
session_start();
include('connessione.php');  // Connessione al database

// Recupera i dettagli del giocatore
$query = "SELECT * FROM giocatori WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':id', $_SESSION['id'], PDO::PARAM_INT);
$stmt->execute();
$giocatore = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$giocatore) {
    die("Errore: Dettagli del giocatore non trovati!");
}

$salute = $giocatore['salute'];
$saldo = $giocatore['saldo'];
$palle = $giocatore['palle'];
$livello_nave = $giocatore['livello_nave'];
$messaggio = "";

// Ottieni i dettagli della nave nemica dal database
$query = "SELECT * FROM navi_nemiche WHERE livello = :livello AND vive = TRUE";
$stmt = $conn->prepare($query);
$stmt->bindParam(':livello', $livello_nave, PDO::PARAM_INT);
$stmt->execute();
$nave = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$nave) {
    die("Errore: Dettagli della nave nemica non trovati!");
}

// Esegui un attacco (semplice o complesso)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $attacco = $_POST['attacco'];

    if ($livello_nave > 5) {
        $danno = 30; // Imposta il danno a 30 dopo il livello 5
    } else {
        $danno = ($attacco == "semplice") ? 15 : 50; 
    }

    if (($attacco == "semplice" && $palle > 0) || ($attacco == "complesso" && $palle >= 5)) {
        $palle -= ($attacco == "semplice") ? 1 : 5;
        $probabilita = 0.75; // Probabilità di successo al 75%

        if (rand(1, 100) <= $probabilita * 100) {
            $nave['vita'] -= $danno;

            // Aggiorna la vita della nave nel database
            $stmt = $conn->prepare("UPDATE navi_nemiche SET vita = :vita WHERE livello = :livello");
            $stmt->bindParam(':vita', $nave['vita'], PDO::PARAM_INT);
            $stmt->bindParam(':livello', $livello_nave, PDO::PARAM_INT);
            $stmt->execute();

            if ($nave['vita'] <= 0) {
                $saldo += $nave['vincita'];
                $messaggio = "Hai distrutto la nave nemica e guadagnato €" . $nave['vincita'] . "!";
                $stmt = $conn->prepare("UPDATE navi_nemiche SET vive = FALSE WHERE livello = :livello");
                $stmt->bindParam(':livello', $livello_nave, PDO::PARAM_INT);
                $stmt->execute();
                $livello_nave++;
                if ($livello_nave > 10) {
                    $livello_nave = 1;
                    $messaggio .= " Congratulazioni, hai vinto! Il gioco ricomincia.";
                }
                $palle = 20;
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
        // Condizione per il messaggio finale e il redirect
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
    $stmt = $conn->prepare("UPDATE giocatori SET salute = :salute, saldo = :saldo, palle = :palle, livello_nave = :livello_nave WHERE id = :id");
    $stmt->execute([':salute' => $salute, ':saldo' => $saldo, ':palle' => $palle, ':livello_nave' => $livello_nave, ':id' => $_SESSION['id']]);

    $_SESSION['saldo'] = $saldo;
    $_SESSION['salute'] = $salute;
    $_SESSION['livello_nave'] = $livello_nave;
    $_SESSION['messaggio'] = $messaggio;

    header("Location: index.php");
    exit();
}
?>
