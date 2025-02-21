<?php
require_once __DIR__ . '/../Controller/CommentiController.php';
require_once __DIR__ . '/../Controller/VotoController.php';
require_once __DIR__ . '/../Model/Utente.php';  // Aggiungi l'importazione per la gestione dell'utente

// Ottieni l'ID dell'escursione da GET
$escursione_id = filter_input(INPUT_GET, 'escursione_id', FILTER_VALIDATE_INT);

if ($escursione_id === false || $escursione_id <= 0) {
    echo "<div class='alert alert-danger'>ID dell'escursione non valido.</div>";
    exit();
}

$commentiController = new CommentiController();
$votoController = new VotoController();
$utente = new Utente();

// Ottieni i commenti per l'escursione
$commenti = $commentiController->getCommenti($escursione_id);

// Recupera l'ID dell'utente loggato
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commenti Escursione</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            color: #333;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }
        h1 {
            color: #3d3d3d;
            font-size: 2em;
            margin-bottom: 20px;
        }
        .card {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .alert {
            font-size: 1.1em;
            padding: 20px;
            text-align: center;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Commenti per l'Escursione</h1>

        <?php if ($commenti): ?>
            <?php foreach ($commenti as $commento): ?>
                <?php
                    // Controlla se l'utente ha già votato
                    $votoUtente = $votoController->getVotoPerCommentoUtente($user_id, $commento['id']);
                    $votato = $votoUtente !== null;
                    $miPiaceCount = $votoController->getLikeDislikeCount($commento['id'], 1); // Mi Piace
                    $nonMiPiaceCount = $votoController->getLikeDislikeCount($commento['id'], -1); // Non Mi Piace
                ?>
                <div class="card mb-3" id="commento_<?php echo $commento['id']; ?>" data-commento-id="<?php echo $commento['id']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($commento['nome']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($commento['commento'])); ?></p>
                        <p><small><?php echo htmlspecialchars($commento['data']); ?></small></p>

                        <div class="mt-3">
                            <p><strong>Mi Piace:</strong> <span id="mi_piace_<?php echo $commento['id']; ?>"><?php echo $miPiaceCount; ?></span> | 
                               <strong>Non Mi Piace:</strong> <span id="non_mi_piace_<?php echo $commento['id']; ?>"><?php echo $nonMiPiaceCount; ?></span></p>

                            <!-- Pulsante per votare -->
                            <button class="btn btn-success btn-sm mi_piace_button" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione_id; ?>" <?php echo $votato ? 'disabled' : ''; ?>>Mi Piace</button>
                            <button class="btn btn-danger btn-sm non_mi_piace_button" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione_id; ?>" <?php echo $votato ? 'disabled' : ''; ?>>Non Mi Piace</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="alert alert-warning">Nessun commento per questa escursione.</div>
        <?php endif; ?>
    </div>

    <script>
    $(document).ready(function() {
        // Gestione del clic sul bottone "Mi Piace"
        $('.mi_piace_button').on('click', function() {
            var commentoId = $(this).data('commento-id');
            var escursioneId = $(this).data('escursione-id');

            // Invia la richiesta AJAX per "Mi Piace"
            $.ajax({
                url: 'aggiungi_like.php',
                type: 'POST',
                data: {
                    commento_id: commentoId,
                    escursione_id: escursioneId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.mi_piace !== undefined && data.non_mi_piace !== undefined) {
                        // Aggiorna i contatori
                        $('#mi_piace_' + commentoId).text(data.mi_piace);
                        $('#non_mi_piace_' + commentoId).text(data.non_mi_piace);

                        // Disabilita i pulsanti
                        $('#commento_' + commentoId).find('.mi_piace_button').prop('disabled', true);
                        $('#commento_' + commentoId).find('.non_mi_piace_button').prop('disabled', true);
                    } else {
                        alert(data.error);
                    }
                },
                error: function() {
                    alert('Errore nella richiesta.');
                }
            });
        });

        // Gestione del clic sul bottone "Non Mi Piace"
        $('.non_mi_piace_button').on('click', function() {
            var commentoId = $(this).data('commento-id');
            var escursioneId = $(this).data('escursione-id');

            // Invia la richiesta AJAX per "Non Mi Piace"
            $.ajax({
                url: 'aggiungi_dislike.php',
                type: 'POST',
                data: {
                    commento_id: commentoId,
                    escursione_id: escursioneId
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.mi_piace !== undefined && data.non_mi_piace !== undefined) {
                        // Aggiorna i contatori
                        $('#mi_piace_' + commentoId).text(data.mi_piace);
                        $('#non_mi_piace_' + commentoId).text(data.non_mi_piace);

                        // Disabilita i pulsanti
                        $('#commento_' + commentoId).find('.mi_piace_button').prop('disabled', true);
                        $('#commento_' + commentoId).find('.non_mi_piace_button').prop('disabled', true);
                    } else {
                        alert(data.error);
                    }
                },
                error: function() {
                    alert('Errore nella richiesta.');
                }
            });
        });
    });
    </script>
</body>
</html>
