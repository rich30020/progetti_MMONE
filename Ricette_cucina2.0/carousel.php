<?php
// Includi la connessione al database
include 'connessione_ricette.php';

// Inizializza un array vuoto per gli URL delle immagini
$imageUrls = [];

// Verifica se c'è una ricetta selezionata tramite sessione
$selectedRecipeImage = null;
if (isset($_SESSION['ricetta_selezionata'])) {
    // Recupera l'immagine della ricetta selezionata
    $ricettaId = $_SESSION['ricetta_selezionata'];
    $sql = "SELECT image_url FROM ricette WHERE id = ?";
    $stmt = $conn_kitchen->prepare($sql);
    $stmt->bind_param('i', $ricettaId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $selectedRecipeImage = $row['image_url'];
    }
    $stmt->close();
}

// Recupera tutte le immagini delle ricette cliccate dal database per il carousel
$sql = "SELECT image_url FROM ricette WHERE clicks > 0";
$result = $conn_kitchen->query($sql);

if ($result->num_rows > 0) {
    // Aggiungi gli URL delle immagini all'array
    while ($row = $result->fetch_assoc()) {
        $imageUrls[] = $row['image_url'];
    }
}

$conn_kitchen->close();
?>

<!-- HTML per il carousel -->
<?php if (!empty($imageUrls)): ?>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php 
            // Se c'è una ricetta selezionata, aggiungila prima delle immagini casuali
            $indicatorOffset = 0;
            if ($selectedRecipeImage) {
                echo '<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>';
                $indicatorOffset = 1;  // Salta il primo indicatore per le immagini casuali
            }
            // Aggiungi gli indicatori per le altre immagini
            foreach ($imageUrls as $index => $imageUrl): 
                if ($selectedRecipeImage && $index === 0) continue; // Evita di duplicare il primo
                echo '<li data-target="#carouselExampleIndicators" data-slide-to="' . ($index + $indicatorOffset) . '" class="' . ($index === 0 && !$selectedRecipeImage ? 'active' : '') . '"></li>';
            endforeach;
            ?>
        </ol>

        <div class="carousel-inner">
            <!-- Mostra l'immagine della ricetta selezionata come prima slide -->
            <?php if ($selectedRecipeImage): ?>
                <div class="carousel-item active">
                    <img class="d-block w-100" src="<?= $selectedRecipeImage ?>" alt="Ricetta Selezionata">
                </div>
            <?php endif; ?>

            <!-- Aggiungi le immagini casuali provenienti dal database -->
            <?php foreach ($imageUrls as $index => $imageUrl): ?>
                <div class="carousel-item <?= $index === 0 && !$selectedRecipeImage ? 'active' : '' ?>">
                    <img class="d-block w-100" src="<?= $imageUrl ?>" alt="Slide <?= $index + 1 ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
<?php else: ?>
    <p>Non ci sono immagini da mostrare.</p>
<?php endif; ?>
