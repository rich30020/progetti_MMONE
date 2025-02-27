<?php

include 'connessione.php';

function aggiornaPunti($conn, $giocatoreId, $punti) {
    $stmt = $conn->prepare("UPDATE giocatori SET punti = punti + ? WHERE giocatore_id = ?");
    $stmt->bind_param("ii", $punti, $giocatoreId);
    $stmt->execute();
    $stmt->close();
}

// Funzione per ottenere i punti del giocatore
function ottieniPunti($conn, $giocatoreId) {
    $stmt = $conn->prepare("SELECT punti FROM giocatori WHERE giocatore_id = ?");
    $stmt->bind_param("i", $giocatoreId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $punti = $row['punti'];
    $stmt->close();
    return $punti;
}

// Funzione per resettare i punti del giocatore
function resetPunti($conn, $giocatoreId) {
    $stmt = $conn->prepare("UPDATE giocatori SET punti = 0 WHERE giocatore_id = ?");
    $stmt->bind_param("i", $giocatoreId);
    $stmt->execute();
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();


    $casoId = intval($_POST['caso_id']);
    $teoria = trim($_POST['teoria']);
    $sospettoSelezionato = trim($_POST['sospetto']);
    $sospettoCorretto = trim($_POST['risposta_corretta']);
    $giocatoreId = 1; 

    $corretto = (strcasecmp($sospettoSelezionato, $sospettoCorretto) === 0) ? 1 : 0;

    // Inserisci la risposta nel database
    $stmt = $conn->prepare("INSERT INTO risposte_giocatori (caso_id, teoria_giocatore, corretto) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $casoId, $teoria, $corretto);
    if ($stmt->execute()) {
        if ($corretto) {
            // Se la risposta è corretta, aggiungi 20 punti
            aggiornaPunti($conn, $giocatoreId, 20); 
            $_SESSION['punti'] += 20; // Aggiorna i punti nella sessione

            // Verifica se il giocatore ha completato l'ultimo caso
            $ultimoCaso = 5;
            if ($casoId == $ultimoCaso) {
                resetPunti($conn, $giocatoreId); 
                header("Location: vittoria.php"); 
                exit();
            } else {
                header("Location: vittoria.php");
                exit();
            }
        } else {
            // Se la risposta è sbagliata, vai alla pagina di sconfitta
            header("Location: sconfitta.php");
            exit();
        }
    } else {

        echo "<p>Errore: " . $stmt->error . "</p>";
    }

    $stmt->close();
    $conn->close();
} else {

    header('Location: caso.php');
    exit();
}
?>
