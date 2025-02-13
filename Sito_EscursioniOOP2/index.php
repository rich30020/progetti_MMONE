<?php
session_start();

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user']) || !$_SESSION['autenticato']) {
    header('Location: login.php'); // Se non autenticato, rimanda al login
    exit();
}

require_once __DIR__ . '/Controller/escursioniController.php';

$escursioniController = new EscursioniController();
$escursioni = $escursioniController->getEscursioni();

include 'view/navbar.php';
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Esplora le Escursioni</title>
</head>
<body>

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>!</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="row">
            <?php foreach ($escursioni as $escursione): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg">
                        <img src="uploads/<?php echo htmlspecialchars($escursione['foto']); ?>" class="card-img-top" alt="Immagine Escursione">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($escursione['sentiero']); ?></h5>
                            <p class="card-text">
                                <strong>Durata:</strong> <?php echo htmlspecialchars($escursione['durata']); ?> ore<br>
                                <strong>Difficoltà:</strong> <?php echo htmlspecialchars($escursione['difficolta']); ?><br>
                                <strong>Punti:</strong> <?php echo htmlspecialchars($escursione['punti']); ?>
                            </p>
                            <a href="view/esplora.php?id=<?php echo urlencode($escursione['id']); ?>" class="btn btn-primary">Esplora</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="view\add_escursione.php" class="btn btn-success mt-4">Aggiungi una nuova escursione</a>
    </div>

</body>
</html>
