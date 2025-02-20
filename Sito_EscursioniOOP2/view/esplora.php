<?php
session_start();

// Verifica che l'utente sia loggato, altrimenti redirige alla pagina di login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/../Controller/EscursioniController.php';
require_once __DIR__ . '/../Controller/CommentiController.php';

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
    <link rel="stylesheet" href="view/style.css">
    <link rel="icon" href="pngwing.com.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <meta name="description" content="Esplora diverse escursioni e condividi le tue esperienze.">
    <style>
        body {
            background: #fff;
            font-family: 'Arial', sans-serif;
            color: #333;
        }

        h1, h4 {
            font-weight: bold;
            color: #004d40;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        p, .card-text, .form-group textarea, .btn {
            color: #555;
            font-size: 1.1em;
        }

        .navbar {
            background: rgba(0, 0, 0, 0.8);
            padding: 1em;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
        }

        .container {
            margin-top: 50px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card-body {
            background: #fff;
            border-radius: 15px;
        }

        .card img {
            border-radius: 15px;
        }

        .btn-primary {
            background: #216ce7;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background:rgb(4, 54, 136);
        }

        .like-dislike-buttons button {
            font-size: 0.8em;
            padding: 5px 10px;
            margin-right: 5px;
        }

        .like-dislike-buttons .btn-success {
            background: #66bb6a;
            border: none;
        }

        .like-dislike-buttons .btn-danger {
            background: #ef5350;
            border: none;
        }

        .like-dislike-buttons .btn-success:hover,
        .like-dislike-buttons .btn-danger:hover {
            opacity: 0.8;
        }

        .comment-section {
            max-height: 120px;
            overflow-y: auto;
            padding: 8px;
            background: #e0f7fa;
            border-radius: 8px;
            font-size: 0.9em;
            display: none;
        }

        .comment-section.show {
            display: block;
            max-height: 200px;
        }

        .comment-section h5 {
            color: #00796b;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .comment-section p {
            color: #555;
            line-height: 1.4;
            margin-bottom: 4px;
        }

        .comment-btn {
            background: #004d40;
            border: none;
            border-radius: 25px;
            padding: 8px 15px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            margin-top: 8px;
        }

        .comment-btn:hover {
            background: #00796b;
        }

        .comment-text {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: block;
        }

        .show-more {
            color: #00695c;
            cursor: pointer;
            font-size: 0.9em;
            font-weight: bold;
            margin-top: 5px;
        }

        .form-control {
            border-radius: 15px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            box-shadow: none;
            margin-top: 10px;
        }

        .form-control:focus {
            border-color: #66bb6a;
        }

        .comment-preview {
            font-size: 0.9em;
            line-height: 1.4;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-5">
        <h1 class="mb-4">Esplora Escursioni</h1>
        <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>! <a href="../logout.php">Logout</a></p>

        <div class="grid-container">
            <?php if (!empty($escursioni)): ?>
                <?php foreach ($escursioni as $escursione): 
                    // Recupera l'utente che ha caricato l'escursione
                    $utente = isset($escursione['user_id']) ? $escursioniController->getUserById($escursione['user_id']) : null;
                ?>
                    <div class="card">
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

                            <!-- Commenti e Interazioni -->
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
                                // Recupera i commenti per l'escursione, ma limitati ai primi 3
                                $comments = $commentiController->getCommenti($escursione['id']);
                                $comments_to_show = array_slice($comments, 0, 3); // Limitato a 3
                                if (!empty($comments_to_show)): ?>
                                    <div class="comment-section">
                                        <?php foreach ($comments_to_show as $commento): 
                                            // Recupera l'utente che ha scritto il commento
                                            $utenteCommento = isset($commento['user_id']) ? $escursioniController->getUserById($commento['user_id']) : null;
                                        ?>
                                            <div class="commento-piccolo">
                                                <strong><?php echo htmlspecialchars($utenteCommento ? $utenteCommento['nome'] : 'Utente sconosciuto'); ?>:</strong>
                                                <p><?php echo htmlspecialchars(substr($commento['commento'], 0, 100)); ?>...</p>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <a href="commenti_completi.php?escursione_id=<?php echo $escursione['id']; ?>" class="mostra-altro-btn">Mostra altri commenti</a>
                                <?php endif; ?>
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
</body>
</html>
