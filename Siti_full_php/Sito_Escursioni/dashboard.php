<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

// includo la connessione al db
include 'connessione.php';

$nome = $_SESSION['nome'];
$sql = "SELECT * FROM utenti WHERE nome='$nome'";
$result = $conn->query($sql);
$utente = $result->fetch_assoc();

$sql = "SELECT * FROM escursioni WHERE user_id=" . $utente['id'];
$escursioni = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Utente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="user-info">
            <h1>Benvenuto, <?php echo $utente['nome']; ?>!</h1>
            <p>Livello di esperienza: <?php echo $utente['livello_esperienza']; ?></p>
            <p>Punti escursionistici: <?php echo $utente['punti_escursionistici']; ?></p>
        </div>
        <div class="user-escursioni">
            <h2>Le tue escursioni:</h2>
            <ul>
                <?php while($row = $escursioni->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo $row['sentiero']; ?></h3>
                        <p>Durata: <?php echo $row['durata']; ?></p>
                        <p>Difficoltà: <?php echo $row['difficolta']; ?></p>
                        <p>Commenti: <?php echo $row['commenti']; ?></p>
                        <p>Punti: <?php echo $row['punti']; ?></p>
                        <?php if ($row['foto']): ?>
                            <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['sentiero']; ?>" style="max-width:200px;">
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <div class="form-container">
        <h2>Aggiungi un nuovo Sentiero</h2>
        <form method="post" action="add_escursione.php" enctype="multipart/form-data">
            <label for="sentiero">Sentiero:</label>
            <input type="text" id="sentiero" name="sentiero" required>
            <br>
            <label for="durata">Durata (HH:MM:SS):</label>
            <input type="text" id="durata" name="durata" required>
            <br>
            <label for="difficolta">Difficoltà:</label>
            <input type="text" id="difficolta" name="difficolta" required>
            <br>
            <label for="commenti">Commenti:</label>
            <textarea id="commenti" name="commenti"></textarea>
            <br>
            <label for="punti">Punti:</label>
            <input type="number" id="punti" name="punti" required>
            <br>
            <label for="foto">Foto del Sentiero:</label>
            <input type="file" id="foto" name="foto">
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
