<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

// Includo la connessione al db 
include 'connessione.php';

$sql = "SELECT * FROM percorsi_eccezionali";
$percorsi = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Task - Sentieri Proposti</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <h1>Sentieri Proposti</h1>
        <div class="task-grid">
            <?php while($row = $percorsi->fetch_assoc()): ?>
                <div class="task-item">
                    <h3><?php echo $row['sentiero']; ?></h3>
                    <p>Durata: <?php echo $row['durata']; ?></p>
                    <p>Difficoltà: <?php echo $row['difficolta']; ?></p>
                    <p>Commenti: <?php echo $row['commenti']; ?></p>
                    <?php if ($row['foto']): ?>
                        <img src="<?php echo $row['foto']; ?>" alt="<?php echo $row['sentiero']; ?>" style="max-width:200px;">
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $conn->close(); ?>
