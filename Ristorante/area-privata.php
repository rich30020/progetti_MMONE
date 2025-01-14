<?php
session_start();

// Verifica se l'utente è loggato
if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("location: login.html");
    exit;
}

// Impostazioni di connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname_kitchen = "ristorante";

// Connessione al database
$conn_kitchen = new mysqli($servername, $username, $password, $dbname_kitchen);

// Verifica se la connessione è riuscita
if ($conn_kitchen->connect_error) {
    die("Connessione fallita: " . $conn_kitchen->connect_error);
}

// Recupera l'ID utente dalla sessione
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    die("Errore: ID utente non trovato. Assicurati di essere loggato.");
}

// Carica le prenotazioni future
$sql = "SELECT tavolo_id, data_ora FROM prenotazioni WHERE data_ora > NOW()";
$result = $conn_kitchen->query($sql);

$prenotazioni_future = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $prenotazioni_future[] = [
            'tavolo_id' => $row['tavolo_id'],
            'data_ora' => $row['data_ora']
        ];
    }
}

// Carica tutti i tavoli e le loro capacità
$sql = "SELECT id, numero_tavolo, posti FROM tavoli";
$result = $conn_kitchen->query($sql);
$tavoli = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tavoli[$row['id']] = [
            'numero_tavolo' => $row['numero_tavolo'],
            'posti' => $row['posti']
        ];
    }
}

// Carica le prenotazioni dell'utente
$reservations = [];
$sql = "SELECT prenotazioni.id, tavoli.numero_tavolo, tavoli.posti, prenotazioni.data_ora, prenotazioni.numero_persone, prenotazioni.status 
        FROM prenotazioni 
        JOIN tavoli ON prenotazioni.tavolo_id = tavoli.id 
        WHERE prenotazioni.utente_id = ?";
$stmt = $conn_kitchen->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = [
            'id' => $row['id'],
            'numero_tavolo' => $row['numero_tavolo'],
            'posti' => $row['posti'],
            'data_ora' => $row['data_ora'],
            'numero_persone' => $row['numero_persone'],
            'status' => $row['status']
        ];
    }
}

// Chiudi la connessione al database
$stmt->close();
$conn_kitchen->close();
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
    <h2 class="text-center mt-5" style="color: black;">Mappa dei Tavoli</h2>
    <div class="table-map">
    <?php 
    // Ciclo sui tavoli per mostrarli
    foreach ($tavoli as $id => $tavolo): 
        // Controlla se il tavolo è prenotato
        $isBooked = false;
        foreach ($prenotazioni_future as $prenotazione) {
            if ($prenotazione['tavolo_id'] == $id) {
                $isBooked = true;
                break;
            }
        }

        if ($isBooked) {
            continue; // Salta questo tavolo se è prenotato
        }
    ?>
        <div class="table-available">
            Tavolo <?= htmlspecialchars($tavolo['numero_tavolo']); ?>
            <br> 
            (<?= htmlspecialchars($tavolo['posti']); ?> posti)
        </div>
    <?php endforeach; ?>
    </div>
</div>

<h2 class="text-center mt-5">Prenota un Tavolo</h2>
<form method="post" action="prenota.php" class="bg-light p-4 rounded shadow-sm">
    <div class="form-group">
        <label for="tavolo_id">Numero Tavolo</label>
        <input type="number" class="form-control" id="tavolo_id" name="tavolo_id" required>
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

<h2 class="text-center mt-5">Le Tue Prenotazioni</h2>
<p class="text-center">Ciao <?php echo htmlspecialchars($_SESSION["nome"]); ?>, ecco le tue prenotazioni:</p>
<div class="row">
    <?php foreach ($reservations as $prenotazione): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tavolo n. <?= htmlspecialchars($prenotazione['numero_tavolo']); ?></h5>
                    <p class="card-text">
                        <strong>Data e Ora:</strong> <?= htmlspecialchars($prenotazione['data_ora']); ?><br>
                        <strong>Numero di Persone:</strong> <?= htmlspecialchars($prenotazione['numero_persone']); ?><br>
                        <strong>Status:</strong> <?= htmlspecialchars(ucfirst($prenotazione['status'])); ?>
                    </p>
                    <form method="post" action="modifica_prenotazione.php">
                        <input type="hidden" name="prenotazione_id" value="<?= htmlspecialchars($prenotazione['id']); ?>">
                        <input type="hidden" name="numero_tavolo" value="<?= htmlspecialchars($prenotazione['numero_tavolo']); ?>">
                        <input type="hidden" name="data_ora" value="<?= htmlspecialchars($prenotazione['data_ora']); ?>">
                        <input type="hidden" name="numero_persone" value="<?= htmlspecialchars($prenotazione['numero_persone']); ?>">
                        <button type="submit" name="modifica" class="btn btn-primary">Modifica</button>
                        <button type="submit" name="annulla" class="btn btn-danger">Annulla</button>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
