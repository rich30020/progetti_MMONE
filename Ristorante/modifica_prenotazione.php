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
$dbname = "ristorante";

// Connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se la connessione è riuscita
if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}

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

        echo '<form method="post" action="modifica_prenotazione.php">
                <input type="hidden" name="prenotazione_id" value="' . htmlspecialchars($prenotazione_id) . '">

                <!-- Numero Tavolo -->
                <div class="form-group">
                    <label for="tavolo_id">Numero Tavolo</label>
                    <select class="form-control" id="tavolo_id" name="tavolo_id" required>
                        ';

                        // Recupera i numeri dei tavoli disponibili
                        $sql_tavoli = "SELECT id, numero FROM tavoli";
                        $result = $conn->query($sql_tavoli);
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['id'] == $tavolo_id) ? 'selected' : ''; // Se il tavolo è già selezionato
                            echo "<option value='" . $row['id'] . "' $selected> Tavolo " . $row['numero'] . "</option>";
                        }

        echo '  </select>
                </div>

                <!-- Data e Ora -->
                <div class="form-group">
                    <label for="data_ora">Data e Ora</label>
                    <input type="datetime-local" class="form-control" id="data_ora" name="data_ora" value="' . htmlspecialchars($data_ora) . '" required>
                </div>

                <!-- Numero di Persone -->
                <div class="form-group">
                    <label for="numero_persone">Numero di Persone</label>
                    <input type="number" class="form-control" id="numero_persone" name="numero_persone" value="' . htmlspecialchars($numero_persone) . '" required>
                </div>

                <button type="submit" name="aggiorna" class="btn btn-primary">Aggiorna Prenotazione</button>
              </form>';

    } elseif (isset($_POST['aggiorna'])) {
        $nuovo_numero_tavolo = $_POST['tavolo_id'];
        $nuova_data_ora = $_POST['data_ora'];
        $nuovo_numero_persone = $_POST['numero_persone'];

        // Verifica se la data e l'ora sono valide (solo alle ore precise o alle mezz'ore)
        $minute = (int)date('i', strtotime($nuova_data_ora));
        if ($minute != 0 && $minute != 30) {
            echo "Errore: puoi prenotare solo alle ore precise o alle mezz'ore.";
        } else {
            // Aggiorna la prenotazione
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

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Prenotazione</title>
    <link rel="stylesheet" href="main.css">
    <style>
        /* Aumenta l'altezza dell'input */
        .form-control {
            height: 50px; /* Imposta un'altezza più alta per gli input */
            font-size: 1.1rem;
        }

        /* Selezione del tavolo */
        select.form-control {
            height: 50px; /* Stesso valore dell'input numerico */
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifica Prenotazione</h2>
        <?php echo $form; ?>
    </div>
</body>
</html>
