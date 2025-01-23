<?php
include 'connessione_ristorante.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utente_id = $_SESSION['user_id'];
    $tavolo_id = $_POST['tavolo_id'];
    $data_ora = $_POST['data_ora'];
    $numero_persone = $_POST['numero_persone'];

    
    $minute = (int)date('i', strtotime($data_ora));
    if ($minute != 0 && $minute != 30) {
        echo "Errore: puoi prenotare solo alle ore precise o alle mezz'ore.";
    } else {
        // Verifica se il tavolo ha abbastanza posti
        $sql = "SELECT posti FROM tavoli WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $tavolo_id);
        $stmt->execute();
        $stmt->bind_result($posti);
        $stmt->fetch();
        $stmt->close();

        if ($numero_persone <= $posti) {
            // Verifica se il tavolo è già prenotato per la stessa data e ora
            $sql = "SELECT COUNT(*) FROM prenotazioni WHERE tavolo_id = ? AND data_ora = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $tavolo_id, $data_ora);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();
            $stmt->close();

            if ($count > 0) {
                echo "Errore: il tavolo è già prenotato per questa data e ora.";
            } else {

                $sql = "INSERT INTO prenotazioni (utente_id, tavolo_id, data_ora, numero_persone, status) VALUES (?, ?, ?, ?, 'confermata')";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iisi", $utente_id, $tavolo_id, $data_ora, $numero_persone);

                if ($stmt->execute()) {
                    echo "Prenotazione avvenuta con successo. <a href='area-privata.php'>Torna alle tue prenotazioni</a>";
                } else {
                    echo "Errore nella prenotazione: " . $stmt->error;
                }
            }
        } else {
            echo "Errore: il numero di persone supera i posti disponibili per il tavolo selezionato.";
        }
    }
}


$sql = "SELECT id, numero_tavolo, posti FROM tavoli";
$result = $conn->query($sql);
$tavoli = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tavoli[$row['id']] = [
            'numero_tavolo' => htmlspecialchars($row['numero_tavolo']),
            'posti' => htmlspecialchars($row['posti'])
        ];
    }
}


$sql = "SELECT tavolo_id, data_ora FROM prenotazioni WHERE data_ora > NOW()";
$result = $conn->query($sql);

$prenotazioni_future = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prenotazioni_future[] = [
            'tavolo_id' => htmlspecialchars($row['tavolo_id']),
            'data_ora' => htmlspecialchars($row['data_ora'])
        ];
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/restaurant.png">
    <title>Gestione Prenotazioni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">

</head>
<body>

<?php include 'navbar.php' ?>

<div class="container">
    <h2 class="text-center mt-5" style="color: black;">Prenota un Tavolo</h2>
    <form method="post" action="" class="bg-light p-4 rounded shadow-sm">
        <div class="form-group">
            <label for="tavolo_id">Numero Tavolo</label>
            <select class="form-control" id="tavolo_id" name="tavolo_id" required>
                <?php foreach ($tavoli as $id => $tavolo): ?>
                    <?php 
                    $isBooked = false;
                    foreach ($prenotazioni_future as $prenotazione) {
                        if ($prenotazione['tavolo_id'] == $id) {
                            $isBooked = true;
                            break;
                        }
                    }
                    ?>
                    <option value="<?= htmlspecialchars($id); ?>" <?= $isBooked ? 'disabled' : ''; ?>>
                        Tavolo <?= htmlspecialchars($tavolo['numero_tavolo']); ?> (<?= htmlspecialchars($tavolo['posti']); ?> posti)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_ora">Data e Ora</label>
            <input type="datetime-local" class="form-control" id="data_ora" name="data_ora" required>
        </div>
        <div class="form-group">
            <label for="numero_persone">Numero di Persone</label>
            <input type="number" class="form-control" id="numero_persone" name="numero_persone" required>
        </div>
        <button type="submit" class="btn btn-success btn-block">Prenota</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
