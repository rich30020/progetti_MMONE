<?php
// Includi la connessione al database se non l'hai già fatto in altro file
include 'connessione_ricette.php';

// Inizializza un array vuoto per gli URL delle immagini
$imageUrls = [];

// Recupera gli URL delle immagini dalla tabella ricette
$sql = "SELECT image_url FROM ricette";
$result = $conn_kitchen->query($sql);

if ($result->num_rows > 0) {
    // Aggiungi gli URL delle immagini all'array
    while ($row = $result->fetch_assoc()) {
        $imageUrls[] = $row['image_url'];
    }
}
?>

<!-- Qui inizia la tua parte HTML -->
<?php if (is_array($imageUrls) && !empty($imageUrls)): ?>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <?php foreach ($imageUrls as $index => $imageUrl): ?>
                <li data-target="#carouselExampleIndicators" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
            <?php endforeach; ?>
        </ol>
        <div class="carousel-inner">
            <?php foreach ($imageUrls as $index => $imageUrl): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
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
