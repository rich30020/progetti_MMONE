<?php
session_start();

require_once __DIR__ . '/../Controller/escursioniController.php';

$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sentiero = htmlspecialchars($_POST['sentiero']);
    $durata = filter_input(INPUT_POST, 'durata', FILTER_VALIDATE_INT);
    $difficolta = htmlspecialchars($_POST['difficolta']);
    $punti = filter_input(INPUT_POST, 'punti', FILTER_VALIDATE_INT);

    $targetDir = '../uploads/';
    $foto = $_FILES['foto']['name'];
    $targetFile = $targetDir . basename($foto);
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileType, $allowedTypes)) {
        $errore = "Formato file non supportato (solo JPG, PNG, GIF).";
    } elseif ($_FILES['foto']['size'] > 5000000) { // Max 5MB
        $errore = "Il file è troppo grande (max 5MB).";
    } elseif (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
        $escursioniController = new EscursioniController();
        // Aggiungi l'escursione al database e ottieni l'ID della nuova escursione
        $idEscursione = $escursioniController->aggiungiEscursione($_SESSION['user_id'], $sentiero, $durata, $difficolta, $punti, $foto);

        if ($idEscursione) {
            // Reindirizza alla pagina 'esplora.php' con l'ID dell'escursione appena aggiunta
            header('Location: esplora.php?id=' . $idEscursione);
            exit();
        } else {
            $errore = 'Errore durante l\'aggiunta dell\'escursione';
        }
    } else {
        $errore = 'Errore nel caricamento della foto.';
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Aggiungi Escursione</title>
</head>
<body>
    <header>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <main>
        <h1>Aggiungi una nuova escursione</h1>

        <?php if (!empty($errore)): ?>
            <p style="color: red;"><?php echo $errore; ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="sentiero">Sentiero:</label>
            <input type="text" name="sentiero" required>

            <label for="durata">Durata (ore):</label>
            <input type="number" name="durata" required>

            <label for="difficolta">Difficoltà:</label>
            <input type="text" name="difficolta" required>

            <label for="punti">Punti:</label>
            <input type="number" name="punti" required>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" required>

            <button type="submit">Aggiungi Escursione</button>
        </form>
    </main>
</body>
</html>
