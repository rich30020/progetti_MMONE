<?php
require_once __DIR__ . '/../Controller/VotoController.php';

// Ottieni l'ID del commento da GET, assicurandosi che sia un intero valido
$commento_id = filter_input(INPUT_GET, 'commento_id', FILTER_VALIDATE_INT);

if ($commento_id === false || $commento_id <= 0) {
    echo "<div class='alert alert-danger'>ID del commento non valido.</div>";
    exit();
}

// Istanzia il controller del voto
$votoController = new VotoController();

// Ottieni i voti per il commento
$voti = $votoController->getVotiPerCommento($commento_id);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voti Commento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Voti del Commento</h1>

        <?php if ($voti): ?>
            <div class="alert alert-info">
                <strong>Mi Piace:</strong> <?php echo htmlspecialchars($voti['mi_piace']); ?><br>
                <strong>Non Mi Piace:</strong> <?php echo htmlspecialchars($voti['non_mi_piace']); ?><br>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">Nessun voto per questo commento.</div>
        <?php endif; ?>
        
    </div>
</body>
</html>
