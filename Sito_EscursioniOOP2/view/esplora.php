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
    <style>
        body {
            background: linear-gradient(120deg, #84fab0 0%, #8fd3f4 100%);
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
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }

        .card-body {
            background: #fff;
            border-radius: 15px;
            transition: background-color 0.3s ease;
        }

        .card:hover .card-body {
            background-color: #f0f0f0;
        }

        .btn-primary {
            background: #00695c;
            border: none;
            border-radius: 50px;
            padding: 10px 20px;
            font-weight: bold;
            transition: background 0.2s ease;
        }

        .btn-primary:hover {
            background: #004d40;
        }

        .like-dislike-buttons button {
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

        .card img {
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

        .comment-section {
            max-height: 200px;
            overflow-y: auto;
            padding: 10px;
            background: #e0f7fa;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            font-size: 0.9em;
            display: none;
            transition: max-height 0.5s ease-out, padding 0.3s ease-out;
        }

        .comment-section.show {
            display: block;
            max-height: 500px;
        }

        .comment-section h5 {
            color: #00796b;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .comment-section p {
            color: #555;
            line-height: 1.4;
            margin-bottom: 5px;
        }

        .comment-btn {
            background: #004d40;
            border: none;
            border-radius: 25px;
            padding: 8px 15px;
            color: white;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
            transition: background 0.3s;
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
                    $utente = $escursioniController->getUserById($escursione['user_id']); ?>
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
                                    <div class="comment-section">
                                        <h5>Commenti:</h5>
                                        <?php foreach ($comments as $commento): 
                                            $utenteCommento = $escursioniController->getUserById($commento['user_id']); ?>
                                            <div class="comment">
                                                <strong><?php echo htmlspecialchars($utenteCommento ? $utenteCommento['nome'] : 'Utente sconosciuto'); ?>:</strong>
                                                <p class="comment-text" id="comment-text-<?php echo $commento['id']; ?>">
                                                    <?php echo htmlspecialchars(substr($commento['commento'], 0, 100)); ?>...
                                                </p>
                                                <span class="show-more" onclick="toggleComment(<?php echo $commento['id']; ?>)">Leggi di più</span>

                                                <div class="like-dislike-buttons">
                                                    <button class="btn btn-success like-btn" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione['id']; ?>">👍 (<span id="like-count-<?php echo $commento['id']; ?>">0</span>)</button>
                                                    <button class="btn btn-danger dislike-btn" data-commento-id="<?php echo $commento['id']; ?>" data-escursione-id="<?php echo $escursione['id']; ?>">👎 (<span id="dislike-count-<?php echo $commento['id']; ?>">0</span>)</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <button class="comment-btn" onclick="toggleComments()">Mostra Commenti</button>
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
    <script src="view/main.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const likeButtons = document.querySelectorAll(".like-btn");
            const dislikeButtons = document.querySelectorAll(".dislike-btn");

            likeButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const commentoId = this.dataset.commentoId;
                    const escursioneId = this.dataset.escursioneId;

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

                        this.disabled = true;
                        document.querySelector(`.dislike-btn[data-commento-id="${commentoId}"]`).disabled = false;
                    });
                });
            });

            dislikeButtons.forEach(button => {
                button.addEventListener("click", function() {
                    const commentoId = this.dataset.commentoId;
                    const escursioneId = this.dataset.escursioneId;

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

                        this.disabled = true;
                        document.querySelector(`.like-btn[data-commento-id="${commentoId}"]`).disabled = false;
                    });
                });
            });
        });

        function toggleComments() {
            const commentSection = document.querySelector('.comment-section');
            commentSection.classList.toggle('show');
        }

        function toggleComment(commentId) {
            const commentText = document.getElementById(`comment-text-${commentId}`);
            const fullText = "<?php echo htmlspecialchars($commento['commento']); ?>"; 
            if (commentText.textContent.length < fullText.length) {
                commentText.textContent = fullText;
            } else {
                commentText.textContent = fullText.substring(0, 100) + '...';
            }
        }
    </script>
</body>
</html>
