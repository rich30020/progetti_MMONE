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
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esplora Escursioni</title>
    <link rel="stylesheet" href="view/style.css">
    <link rel="icon" href="pngwing.com.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="description" content="Esplora diverse escursioni e condividi le tue esperienze.">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-5">
        <h1 class="mb-4">Esplora Escursioni</h1>
        <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>! <a href="../logout.php">Logout</a></p>

        <div class="row">
            <?php if (!empty($escursioni)): ?>
                <?php foreach ($escursioni as $escursione): 
                    $utente = $escursioniController->getUserById($escursione['user_id']); ?>
                    <div class="col-md-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo htmlspecialchars($escursione['sentiero']); ?> <small class="text-muted">by <?php echo htmlspecialchars($utente ? $utente['nome'] : 'Utente sconosciuto'); ?></small></h3>
                                <p class="card-text"><strong>Durata:</strong> <?php echo htmlspecialchars($escursione['durata']); ?> ore</p>
                                <p class="card-text"><strong>Difficoltà:</strong> <?php echo htmlspecialchars($escursione['difficolta']); ?></p>
                                <p class="card-text"><strong>Punti:</strong> <?php echo htmlspecialchars($escursione['punti']); ?></p>

                                <?php if (!empty($escursione['foto']) && file_exists('../uploads/' . $escursione['foto'])): ?>
                                    <img src="../uploads/<?php echo htmlspecialchars($escursione['foto']); ?>" alt="<?php echo htmlspecialchars($escursione['sentiero']); ?>" class="card-img-top">
                                <?php else: ?>
                                    <p>Nessuna immagine disponibile</p>
                                <?php endif; ?>

                                <div class="interazioni">
                                    <?php if (isset($_SESSION['user'])): ?>
                                        <form method="post" action="aggiungi_commento.php" class="mt-3">
                                            <input type="hidden" name="escursione_id" value="<?php echo $escursione['id']; ?>">
                                            <div class="form-group">
                                                <textarea name="commento" class="form-control" placeholder="Aggiungi un commento..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Commenta</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php 
                                    $comments = $commentiController->getCommenti($escursione['id']);
                                    if (!empty($comments)): ?>
                                        <h4 class="mt-4">Commenti:</h4>
                                        <?php foreach ($comments as $commento): 
                                            $utenteCommento = $escursioniController->getUserById($commento['user_id']); ?>
                                            <div class="card mt-2">
                                                <div class="card-body">
                                                    <strong><?php echo htmlspecialchars($utenteCommento ? $utenteCommento['nome'] : 'Utente sconosciuto'); ?>:</strong>
                                                    <p><?php echo htmlspecialchars($commento['commento']); ?></p>

                                                    <div class="like-dislike-buttons">
                                                        <button class="btn btn-success like-btn" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione['id']; ?>">👍 (<span id="like-count-<?php echo $commento['id']; ?>">0</span>)</button>
                                                        <button class="btn btn-danger dislike-btn" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione['id']; ?>">👎 (<span id="dislike-count-<?php echo $commento['id']; ?>">0</span>)</button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Non ci sono escursioni da visualizzare.</p>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="view/main.js"></script>
    <script>
document.addEventListener("DOMContentLoaded", function() {
    // Gestisci il click sul bottone del like
    const likeButtons = document.querySelectorAll(".like-btn");
    const dislikeButtons = document.querySelectorAll(".dislike-btn");

    likeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const commentoId = this.dataset.commentoId;
            const escursioneId = this.dataset.escursioneId;

            // Chiamata AJAX per aggiungere il like
            fetch('aggiungi_like.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'commento_id': commentoId,
                    'escursione_db': escursioneId
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`like-count-${commentoId}`).textContent = data.like_count;
                document.getElementById(`dislike-count-${commentoId}`).textContent = data.dislike_count;

                // Disabilita il bottone like e abilita il bottone dislike
                this.disabled = true;
                document.querySelector(`.dislike-btn[data-commento-id="${commentoId}"]`).disabled = false;
            });
        });
    });

    // Gestisci il click sul bottone del dislike
    dislikeButtons.forEach(button => {
        button.addEventListener("click", function() {
            const commentoId = this.dataset.commentoId;
            const escursioneId = this.dataset.escursioneId;

            // Chiamata AJAX per aggiungere il dislike
            fetch('aggiungi_dislike.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    'commento_id': commentoId,
                    'escursione_db': escursioneId
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(`like-count-${commentoId}`).textContent = data.like_count;
                document.getElementById(`dislike-count-${commentoId}`).textContent = data.dislike_count;

                // Disabilita il bottone dislike e abilita il bottone like
                this.disabled = true;
                document.querySelector(`.like-btn[data-commento-id="${commentoId}"]`).disabled = false;
            });
        });
    });
});
</script>
</body>
</html>
