
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">

    <title>Document</title>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
    header("location: login.html");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ristorante";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $utente_id = $_SESSION['user_id'];
    $tavolo_id = $_POST['tavolo_id'];
    $data_ora = $_POST['data_ora'];
    $numero_persone = $_POST['numero_persone'];

    // Verifica se il tavolo ha abbastanza posti
    $sql = "SELECT posti FROM tavoli WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $tavolo_id);
    $stmt->execute();
    $stmt->bind_result($posti);
    $stmt->fetch();
    $stmt->close();

    if ($numero_persone <= $posti) {
        $sql = "INSERT INTO prenotazioni (utente_id, tavolo_id, data_ora, numero_persone, status) VALUES (?, ?, ?, ?, 'confermata')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisi", $utente_id, $tavolo_id, $data_ora, $numero_persone);

        if ($stmt->execute()) {
            echo "Prenotazione avvenuta con successo. <a href='area-privata.php'>Torna alle tue prenotazioni</a>";
        } else {
            echo "Errore nella prenotazione: " . $stmt->error;
        }
    } else {
        echo "Errore: il numero di persone supera i posti disponibili per il tavolo selezionato.";
    }

    $stmt->close();
    $conn->close();
}
?>
</body>
</html>