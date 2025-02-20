<?php
require_once __DIR__ . '/../Controller/CommentiController.php';
require_once __DIR__ . '/../Controller/VotoController.php';

// Ottieni l'ID dell'escursione da GET
$escursione_id = filter_input(INPUT_GET, 'escursione_id', FILTER_VALIDATE_INT);

if ($escursione_id === false || $escursione_id <= 0) {
    echo "<div class='alert alert-danger'>ID dell'escursione non valido.</div>";
    exit();
}

$commentiController = new CommentiController();

// Ottieni i commenti per l'escursione
$commenti = $commentiController->getCommenti($escursione_id);

?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commenti Escursione</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Commenti per l'Escursione</h1>

        <?php if ($commenti): ?>
            <?php foreach ($commenti as $commento): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($commento['nome']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($commento['commento'])); ?></p>
                        <p><small><?php echo htmlspecialchars($commento['data']); ?></small></p>

                        <div class="mt-3">
                            <p><strong>Mi Piace:</strong> <?php echo htmlspecialchars($commento['mi_piace']); ?> | 
                               <strong>Non Mi Piace:</strong> <?php echo htmlspecialchars($commento['non_mi_piace']); ?></p>
                            
                            <!-- Pulsante per votare -->
                            <form method="POST" action="vota_commento.php">
                                <input type="hidden" name="commento_id" value="<?php echo $commento['id']; ?>">
                                <input type="hidden" name="escursione_id" value="<?php echo $escursione_id; ?>">
                                <button type="submit" name="voto" value="mi_piace" class="btn btn-success btn-sm">Mi Piace</button>
                                <button type="submit" name="voto" value="non_mi_piace" class="btn btn-danger btn-sm">Non Mi Piace</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">Nessun commento per questa escursione.</div>
        <?php endif; ?>
    </div>
</body>
</html>
