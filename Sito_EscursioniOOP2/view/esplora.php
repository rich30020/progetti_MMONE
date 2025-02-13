<?php
// Evita il richiamo di session_start() se la sessione è già attiva
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Controller/escursioniController.php';
require_once __DIR__ . '/../Controller/CommentiController.php';
require_once __DIR__ . '/../Controller/UtentiController.php'; // Aggiungi il controller utenti

$escursioniController = new EscursioniController();
$commentiController = new CommentiController();

// Recupera tutte le escursioni
$escursioni = $escursioniController->getEscursioni();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esplora Escursioni</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="./main.css">
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="content">
        <h1>Esplora Escursioni</h1>

        <!-- Verifica se l'utente è loggato -->
        <?php if (isset($_SESSION['utente_id'])): ?>
            <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['nome']); ?>! <a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p>Non sei loggato. <a href="../login.php">Accedi</a></p>
        <?php endif; ?>

        <div class="escursioni-grid">
        <?php
        // Verifica se ci sono escursioni e se l'array non è vuoto
        if (isset($escursioni) && !empty($escursioni)) {
            // Itera sull'array di escursioni
            foreach ($escursioni as $escursione) {
                // Recupera il nome dell'utente che ha creato l'escursione
                $utente = $escursioniController->getUserById($escursione['user_id']);
                echo '<div class="escursione-item">';

                // Verifica se l'utente esiste prima di accedere ai suoi dati
                if ($utente !== false) {
                    echo '<h3>' . htmlspecialchars($escursione['sentiero']) . ' <small>by ' . htmlspecialchars($utente['nome']) . '</small></h3>';
                } else {
                    echo '<h3>' . htmlspecialchars($escursione['sentiero']) . ' <small>by Utente sconosciuto</small></h3>';
                }

                echo '<p><strong>Durata:</strong> ' . htmlspecialchars($escursione['durata']) . ' ore</p>';
                echo '<p><strong>Difficoltà:</strong> ' . htmlspecialchars($escursione['difficolta']) . '</p>';
                echo '<p><strong>Punti:</strong> ' . htmlspecialchars($escursione['punti']) . '</p>';

                // Gestione foto
                if (!empty($escursione['foto'])) { 
                    $imagePath = '../uploads/' . htmlspecialchars($escursione['foto']);
                    if (file_exists($imagePath)) {
                        echo '<img src="' . $imagePath . '" alt="' . htmlspecialchars($escursione['sentiero']) . '" style="max-width:200px;">';
                    } else {
                        echo '<p>Immagine non trovata</p>';
                    }
                } else {
                    echo '<p>Nessuna immagine disponibile</p>';
                }

                // Interazioni per commenti, mi piace e non mi piace
                echo '<div class="interazioni">';

                // Form per aggiungere un commento
                if (isset($_SESSION['utente_id'])) { 
                    echo '<form method="post" action="aggiungi_commento.php">';
                    echo '<input type="hidden" name="escursione_id" value="' . $escursione['id'] . '">';
                    echo '<textarea name="commento" placeholder="Aggiungi un commento..." required></textarea>';
                    echo '<button type="submit">Commenta</button>';
                    echo '</form>';
                } else {
                    echo '<p>Accedi per aggiungere un commento.</p>';
                }

                // Recupero dei contatori Mi Piace e Non Mi Piace
                $likeDislikeCount = $escursioniController->getLikeDislikeCount($escursione['id'], 1); // Mi Piace
                $miPiace = $likeDislikeCount['mi_piace'];  // Accedi al valore di "mi_piace"
                $nonMiPiace = $likeDislikeCount['non_mi_piace'];  // Accedi al valore di "non_mi_piace"

                // Pulsanti per Mi Piace e Non Mi Piace con contatori
                echo '<form method="post" action="aggiungi_like.php">';
                echo '<input type="hidden" name="escursione_id" value="' . $escursione['id'] . '">';
                echo '<button type="submit">&#128077; Mi Piace (' . $miPiace . ')</button>';
                echo '</form>';

                echo '<form method="post" action="aggiungi_dislike.php">';
                echo '<input type="hidden" name="escursione_id" value="' . $escursione['id'] . '">';
                echo '<button type="submit">&#128078; Non Mi Piace (' . $nonMiPiace . ')</button>';
                echo '</form>';

                echo '</div>'; // Fine interazioni

                // Recupera i commenti esistenti
                $comments = $commentiController->getCommenti($escursione['id']);
                if (!empty($comments)) {
                    echo '<h4>Commenti:</h4>';
                    foreach ($comments as $commento) {
                        echo '<div class="commento">';
                        $utenteCommento = $escursioniController->getUserById($commento['user_id']);
                        echo '<strong>' . htmlspecialchars($utenteCommento['nome']) . ':</strong>';
                        echo '<p>' . htmlspecialchars($commento['commento']) . '</p>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Non ci sono commenti per questa escursione.</p>';
                }

                echo '</div>'; // Fine escursione-item
            }
        } else {
            echo '<p>Non ci sono escursioni da visualizzare.</p>';
        }
        ?>

        </div>
    </div>

</body>
</html>
