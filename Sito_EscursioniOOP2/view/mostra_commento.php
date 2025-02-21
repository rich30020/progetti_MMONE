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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Commenti per l'Escursione</h1>

        <?php if ($commenti): ?>
            <?php foreach ($commenti as $commento): ?>
                <div class="card mb-3" id="commento_<?php echo $commento['id']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($commento['nome']); ?></h5>
                        <p class="card-text"><?php echo nl2br(htmlspecialchars($commento['commento'])); ?></p>
                        <p><small><?php echo htmlspecialchars($commento['data']); ?></small></p>

                        <div class="mt-3">
                            <p><strong>Mi Piace:</strong> <span id="mi_piace_<?php echo $commento['id']; ?>"><?php echo htmlspecialchars($commento['mi_piace']); ?></span> | 
                               <strong>Non Mi Piace:</strong> <span id="non_mi_piace_<?php echo $commento['id']; ?>"><?php echo htmlspecialchars($commento['non_mi_piace']); ?></span></p>

                            <!-- Pulsante per votare -->
                            <button class="btn btn-success btn-sm mi_piace_button" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione_id; ?>">Mi Piace</button>
                            <button class="btn btn-danger btn-sm non_mi_piace_button" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione_id; ?>">Non Mi Piace</button>
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

            // Se l'utente ha già premuto "Non Mi Piace", disabilita quel bottone
            if ($('#non_mi_piace_' + commentoId).hasClass('clicked')) {
                $('#non_mi_piace_' + commentoId).removeClass('clicked');
                $('#non_mi_piace_' + commentoId).removeClass('btn-danger').addClass('btn-secondary');
            }

            // Se l'utente ha già premuto "Mi Piace", non fare nulla
            if ($(this).hasClass('clicked')) {
                return;
            }

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

                        // Aggiorna il bottone "Mi Piace" per evidenziarlo come selezionato
                        $('.mi_piace_button').removeClass('clicked');
                        $(this).addClass('clicked');
                        $(this).removeClass('btn-success').addClass('btn-success clicked');
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

            // Se l'utente ha già premuto "Mi Piace", disabilita quel bottone
            if ($('#mi_piace_' + commentoId).hasClass('clicked')) {
                $('#mi_piace_' + commentoId).removeClass('clicked');
                $('#mi_piace_' + commentoId).removeClass('btn-success').addClass('btn-secondary');
            }

            // Se l'utente ha già premuto "Non Mi Piace", non fare nulla
            if ($(this).hasClass('clicked')) {
                return;
            }

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

                        // Aggiorna il bottone "Non Mi Piace" per evidenziarlo come selezionato
                        $('.non_mi_piace_button').removeClass('clicked');
                        $(this).addClass('clicked');
                        $(this).removeClass('btn-danger').addClass('btn-danger clicked');
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
