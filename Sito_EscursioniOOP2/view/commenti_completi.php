<?php
session_start();

// Includiamo il controller per i commenti
require_once __DIR__ . '/../Controller/CommentiController.php';

// Creiamo un'istanza del controller
$commentiController = new CommentiController();

// Otteniamo l'ID dell'escursione
$escursione_id = isset($_GET['escursione_id']) ? $_GET['escursione_id'] : 0;

// Otteniamo i commenti
$commenti = $commentiController->getCommenti($escursione_id);

// Gestione dei voti (Mi Piace / Non mi Piace)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    if (isset($_POST['mi_piace'])) {
        $commento_id = $_POST['mi_piace'];
        // Rimuoviamo eventuali voti precedenti
        $commentiController->rimuoviVoto($commento_id, $_SESSION['user_id']);
        // Aggiungiamo il voto "Mi Piace"
        $commentiController->incrementaMiPiace($commento_id);
        // Registriamo il voto
        $commentiController->registraVoto($commento_id, $_SESSION['user_id'], 'mi_piace');
    }

    if (isset($_POST['non_mi_piace'])) {
        $commento_id = $_POST['non_mi_piace'];
        // Rimuoviamo eventuali voti precedenti
        $commentiController->rimuoviVoto($commento_id, $_SESSION['user_id']);
        // Aggiungiamo il voto "Non mi Piace"
        $commentiController->incrementaNonMiPiace($commento_id);
        // Registriamo il voto
        $commentiController->registraVoto($commento_id, $_SESSION['user_id'], 'non_mi_piace');
    }

    // Ricarichiamo la pagina per vedere gli aggiornamenti
    header("Location: commenti_completi.php?escursione_id=$escursione_id");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commenti Completi</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styles migliorati */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fafafa;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            margin-top: 30px;
            color: #343a40;
        }

        .comment-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .comment {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .comment:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .comment p {
            font-size: 1.1rem;
            color: #555;
        }

        .comment p strong {
            color: #007bff;
        }

        .comment-meta {
            font-size: 0.9rem;
            color: #888;
        }

        .like-dislike-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            margin-right: 10px;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .like-btn {
            background-color: #28a745;
            color: white;
        }

        .dislike-btn {
            background-color: #dc3545;
            color: white;
        }

        .like-dislike-btn:disabled {
            background-color: #ddd;
            cursor: not-allowed;
        }

        .like-dislike-btn:hover:not(:disabled) {
            opacity: 0.8;
        }

        .comment-actions {
            display: flex;
            align-items: center;
        }

        .comment-actions .like-dislike-btn {
            margin-right: 15px;
        }

        .back-btn {
            margin: 20px auto;
            display: block;
            padding: 10px 20px;
            font-size: 1.2rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Commenti Completi</h1>

<!-- Bottone per tornare a esplora.php -->
<a href="esplora.php" class="back-btn">Torna a Esplora</a>

<div class="comment-container">
    <?php if (!empty($commenti)): ?>
        <?php foreach ($commenti as $commento): ?>
            <div class="comment">
                <p><strong><?php echo htmlspecialchars($commento['user_id']); ?>:</strong> <?php echo htmlspecialchars($commento['commento']); ?></p>
                <div class="comment-meta">
                    <p>Mi Piace: <?php echo $commento['mi_piace']; ?> | Non mi Piace: <?php echo $commento['non_mi_piace']; ?></p>
                </div>

                <form method="post" action="commenti_completi.php?escursione_id=<?php echo $escursione_id; ?>" class="comment-actions">
                    <?php
                    // Verifica se l'utente ha già votato
                    $voto = isset($_SESSION['voti_commenti'][$commento['id']]) ? $_SESSION['voti_commenti'][$commento['id']] : null;
                    ?>

                    <button type="submit" name="mi_piace" value="<?php echo $commento['id']; ?>" 
                            class="like-dislike-btn like-btn <?php echo ($voto == 'mi_piace') ? 'disabled' : ''; ?>" 
                            <?php echo ($voto == 'mi_piace') ? 'disabled' : ''; ?>>
                        <i class="fas fa-thumbs-up"></i> Mi Piace
                    </button>

                    <button type="submit" name="non_mi_piace" value="<?php echo $commento['id']; ?>" 
                            class="like-dislike-btn dislike-btn <?php echo ($voto == 'non_mi_piace') ? 'disabled' : ''; ?>" 
                            <?php echo ($voto == 'non_mi_piace') ? 'disabled' : ''; ?>>
                        <i class="fas fa-thumbs-down"></i> Non mi Piace
                    </button>
                </form>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nessun commento disponibile.</p>
    <?php endif; ?>
</div>

</body>
</html>
