<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

// includo la connessione al db
include 'connessione.php';

// ottengo tutte le escursioni
$sql = "
    SELECT escursioni.*, utenti.nome AS nome_utente,
           escursioni.mi_piace AS numero_mi_piace,
           escursioni.non_mi_piace AS numero_non_mi_piace
    FROM escursioni
    JOIN utenti ON escursioni.user_id = utenti.id
";
$escursioni = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esplora Escursioni</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <h1>Esplora Escursioni</h1>
        <div class="escursioni-grid">
            <?php while($row = $escursioni->fetch_assoc()): ?>
                <div class="escursione-item">
                    <h3><?php echo $row['sentiero']; ?> <small>by <?php echo $row['nome_utente']; ?></small></h3>
                    <p>Durata: <?php echo $row['durata']; ?></p>
                    <p>Difficoltà: <?php echo $row['difficolta']; ?></p>
                    <p align="justify">Commenti: <?php echo $row['commenti']; ?></p>
                    <p>Punti: <?php echo $row['punti']; ?></p>
                    <?php if ($row['foto']): ?>
                        <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['sentiero']; ?>" style="max-width:200px;">
                    <?php endif; ?>
                    <div class="interazioni">
                        <form method="post" action="aggiungi_commento.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <textarea name="commento" placeholder="Aggiungi un commento..." required></textarea>
                            <button type="submit">Commenta</button>
                        </form>
                        <form method="post" action="aggiungi_like.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128077; Mi Piace (<?php echo $row['numero_mi_piace']; ?>)</button>
                        </form>
                        <form method="post" action="aggiungi_dislike.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128078; Non Mi Piace (<?php echo $row['numero_non_mi_piace']; ?>)</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
