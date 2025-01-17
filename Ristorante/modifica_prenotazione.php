<?php
include 'connessione_ristorante.php';

$form = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $prenotazione_id = $_POST['prenotazione_id'];

    if (isset($_POST['modifica'])) {
        // Recupera i dettagli della prenotazione
        $sql = "SELECT tavolo_id, data_ora, numero_persone FROM prenotazioni WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $prenotazione_id);
        $stmt->execute();
        $stmt->bind_result($tavolo_id, $data_ora, $numero_persone);
        $stmt->fetch();
        $stmt->close();

        include 'form.php'; 
    } elseif (isset($_POST['aggiorna'])) {
        $nuovo_numero_tavolo = $_POST['tavolo_id'];
        $nuova_data_ora = $_POST['data_ora'];
        $nuovo_numero_persone = $_POST['numero_persone'];

        $minute = (int)date('i', strtotime($nuova_data_ora));
        if ($minute != 0 && $minute != 30) {
            echo "Errore: puoi prenotare solo alle ore precise o alle mezz'ore.";
        } else {
            $sql = "UPDATE prenotazioni SET tavolo_id=?, data_ora=?, numero_persone=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isii", $nuovo_numero_tavolo, $nuova_data_ora, $nuovo_numero_persone, $prenotazione_id);

            if ($stmt->execute()) {
                echo "Prenotazione modificata con successo. <a href='area-privata.php'>Torna alle tue prenotazioni</a>";
            } else {
                echo "Errore nella modifica della prenotazione: " . htmlspecialchars($stmt->error);
            }
        }
    } elseif (isset($_POST['annulla'])) {
        $sql = "DELETE FROM prenotazioni WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $prenotazione_id);

        if ($stmt->execute()) {
            echo "Prenotazione annullata con successo. <a href='area-privata.php'>Torna alle tue prenotazioni</a>";
        } else {
            echo "Errore nell'annullamento della prenotazione: " . htmlspecialchars($stmt->error);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Prenotazione</title>
    <link rel="stylesheet" href="main.css">
    <style>
        .form-control {
            height: 50px; 
            font-size: 1.1rem;
        }

        select.form-control {
            height: 50px; 
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <?php
    if (!empty($form)) {
        echo $form;
    }
    ?>
</body>
</html>
