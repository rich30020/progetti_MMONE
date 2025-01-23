<?php
include 'connessione_ristorante.php'; 


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
$tavoli = []; // Inizializza la variabile $tavoli come un array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tavoli[$row['id']] = [
            'numero_tavolo' => $row['numero_tavolo'],
            'posti' => $row['posti']
        ];
    }
}

// Verifica se ci sono tavoli
if (empty($tavoli)) {
    echo "<p>Nessun tavolo disponibile.</p>";
}


$conn->close();
?>

<div class="container">
    <h2 class="text-center mt-5" style="color: black;">Mappa dei Tavoli</h2>
    <div class="table-map">
        <?php 
        // Mostra i tavoli solo se $tavoli non è vuoto
        if (!empty($tavoli)) {
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
                    continue; // Salta questo tavolo
                }
        ?>
            
            <div class="table-available">
                <img src="images/table.svg" alt="Tavolo <?= htmlspecialchars($tavolo['numero_tavolo']); ?>" width="100" height="100">
                <br>
                Tavolo <?= htmlspecialchars($tavolo['numero_tavolo']); ?>
                <br>
                (<?= htmlspecialchars($tavolo['posti']); ?> posti)
            </div>
        <?php 
            endforeach;
        } 
        ?>
    </div>
</div>
