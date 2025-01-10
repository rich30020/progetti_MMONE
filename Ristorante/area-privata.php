<?php
session_start(); // Avvia la sessione

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("location: login.html"); // Reindirizza se non loggato
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname_kitchen = "ristorante";

// Connessione al database
$conn_kitchen = new mysqli($servername, $username, $password, $dbname_kitchen);

if ($conn_kitchen->connect_error) {
    die("Connessione fallita: " . $conn_kitchen->connect_error); // Mostra errore connessione
}

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
} else {
    die("Errore: ID utente non trovato. Assicurati di essere loggato.");
}

// Carica le prenotazioni dell'utente dal database all'array $reservations
$reservations = [];
$tavoli = [];
$tabella_stato = [];

// Carica tutte le prenotazioni future
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
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tavoli[$row['id']] = [
            'numero_tavolo' => $row['numero_tavolo'],
            'posti' => $row['posti']
        ];
        $tabella_stato[$row['id']] = 'disponibile'; // Segna inizialmente tutti i tavoli come disponibili
    }
}

// Aggiorna lo stato dei tavoli basato sulle prenotazioni future
foreach ($prenotazioni_future as $prenotazione) {
    $tavolo_id = $prenotazione['tavolo_id'];
    if (isset($tabella_stato[$tavolo_id])) {
        $tabella_stato[$tavolo_id] = 'occupato'; // Segna i tavoli come occupati se prenotati
    }
}

// Carica le prenotazioni dell'utente
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

$stmt->close();
$conn_kitchen->close(); // Chiude la connessione al database
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/kitchen.png">
    <title>Gestione Prenotazioni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .table-available {
            background-color: green;
            color: white;
            padding: 10px;
            margin: 5px;
        }
        .table-occupied {
            background-color: red;
            color: white;
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>

<?php include 'navbar.php' ?>

<div class="container">
    <h2 class="text-center mt-5" style="color: black;">Mappa dei Tavoli</h2>
    <div class="table-map">
        <?php foreach ($tabella_stato as $id => $status): ?>
            <div class="<?= $status === 'disponibile' ? 'table-available' : 'table-occupied'; ?>">
                Tavolo <?= $tavoli[$id]['numero_tavolo']; ?>
                <br> 
                (<?= $tavoli[$id]['posti']; ?> posti)
            </div>
        <?php endforeach; ?>
    </div>
    <!-- Resto del tuo codice HTML -->
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
<p class="text-center">Ciao <?php echo $_SESSION["nome"]; ?>, ecco le tue prenotazioni:</p>
<div class="row">
    <?php foreach ($reservations as $prenotazione): ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Tavolo n. <?= $prenotazione['numero_tavolo']; ?></h5>
                    <p class="card-text">
                        <strong>Data e Ora:</strong> <?= $prenotazione['data_ora']; ?><br>
                        <strong>Numero di Persone:</strong> <?= $prenotazione['numero_persone']; ?><br>
                        <strong>Status:</strong> <?= ucfirst($prenotazione['status']); ?>
                    </p>
                    <form method="post" action="modifica_prenotazione.php">
                        <input type="hidden" name="prenotazione_id" value="<?= $prenotazione['id']; ?>">
                        <input type="hidden" name="numero_tavolo" value="<?= $prenotazione['numero_tavolo']; ?>">
                        <input type="hidden" name="data_ora" value="<?= $prenotazione['data_ora']; ?>">
                        <input type="hidden" name="numero_persone" value="<?= $prenotazione['numero_persone']; ?>">
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
