<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../Controller/EscursioniController.php';
require_once __DIR__ . '/../Controller/CommentiController.php';

$escursioniController = new EscursioniController();
$commentiController = new CommentiController();
$escursioni = $escursioniController->getEscursioni();
$user = $_SESSION['user'];

function mostraStelle($bellezza) {
    return str_repeat('⭐', $bellezza);
}

// Invio commento
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commento']) && isset($_POST['escursione_id'])) {
    $commento = $_POST['commento'];
    $escursione_id = $_POST['escursione_id'];
    $user_id = $user['id'];

    if (!empty($commento)) {
        $commentiController->aggiungiCommento($escursione_id, $user_id, $commento);
        header("Location: esplora.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esplora Escursioni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1>Ciao, <?php echo htmlspecialchars($user['nome']); ?>! Esplora Escursioni</h1>

        <div class="row">
            <?php if (!empty($escursioni)): ?>
                <?php foreach ($escursioni as $escursione): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($escursione['sentiero']); ?></h3>
                                <p><strong>Durata:</strong> <?php echo htmlspecialchars($escursione['durata']); ?> ore</p>
                                <p><strong>Difficoltà:</strong> <?php echo htmlspecialchars($escursione['difficolta']); ?></p>
                                <p><strong>Punti:</strong> <?php echo htmlspecialchars($escursione['punti']); ?></p>
                                <p><strong>Bellezza:</strong> <?php echo mostraStelle($escursione['bellezza']); ?></p>
                                
                                <?php if (!empty($escursione['foto']) && file_exists('../uploads/' . $escursione['foto'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($escursione['foto']); ?>" alt="<?php echo htmlspecialchars($escursione['sentiero']); ?>" class="img-fluid">
                                <?php else: ?>
                                    <p>Nessuna immagine disponibile</p>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <a href="mostra_commento.php?escursione_id=<?php echo $escursione['id']; ?>" class="btn btn-secondary">Mostra Commenti</a>
                                </div>

                                <div class="mt-3">
                                    <form method="post" action="esplora.php">
                                        <input type="hidden" name="escursione_id" value="<?php echo $escursione['id']; ?>">
                                        <textarea name="commento" class="form-control" rows="3" placeholder="Aggiungi un commento..." required></textarea>
                                        <button type="submit" class="btn btn-primary mt-2">Aggiungi Commento</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Non ci sono escursioni disponibili.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
