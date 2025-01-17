<?php
include 'connessione_ristorante.php'; 

// Carica le prenotazioni future
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

// Chiudi la connessione
$conn->close();
?>

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
