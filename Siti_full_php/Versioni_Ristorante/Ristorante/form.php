<?php
include 'connessione_ristorante.php';

// Carica le prenotazioni
$sql = "SELECT tavolo_id, data_ora FROM prenotazioni WHERE data_ora > NOW()";
$result = $conn->query($sql);

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
$result = $conn->query($sql);
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
$stmt = $conn->prepare($sql);
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
$conn->close();
?>


<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="main.css">
<h2 class="text-center mt-5">Prenota un Tavolo</h2>
<form method="post" action="prenota.php" class="bg-light p-4 rounded shadow-sm">
    <div class="form-group">
        <label for="tavolo_id">Numero Tavolo</label>
        <select class="form-control" id="tavolo_id" name="tavolo_id" required>
            <option value="" disabled selected>Scegli un Tavolo</option>
            <?php 
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
                    continue;
                }
            ?>
                <option value="<?= htmlspecialchars($id); ?>">
                    Tavolo <?= htmlspecialchars($tavolo['numero_tavolo']); ?> - (<?= htmlspecialchars($tavolo['posti']); ?> posti)
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
        <select class="form-control" id="numero_persone" name="numero_persone" required>
            <?php for ($i = 1; $i <= 10; $i++): ?>
                <option value="<?= $i; ?>"><?= $i; ?> persone</option>
            <?php endfor; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success btn-block">Prenota</button>
</form>