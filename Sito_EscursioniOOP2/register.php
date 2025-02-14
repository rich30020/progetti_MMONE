<?php
require_once __DIR__ . '/Controller/RegisterController.php'; // Corretto il nome del controller

$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $eta = $_POST['eta'];
    $livello_esperienza = $_POST['livello_esperienza'];

    $registerController = new RegisterController();
    $errore = $registerController->register($nome, $email, $password, $eta, $livello_esperienza);
    
    // Se la registrazione è riuscita, reindirizza al login
    if ($errore === "Registrazione riuscita!") {
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/cc/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Registrati</title>
</head>
<body>
    <div class="circle"></div>
    <div class="card">
        <div class="logo">
            <i class="bx bx-bitcoin"></i>
        </div>
        <h2>Crea il tuo account</h2>
        <form class="form" method="POST">
            <input type="text" name="nome" placeholder="Nome" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="number" name="eta" placeholder="Età" required>
            <input type="text" name="livello_esperienza" placeholder="Livello di Esperienza" required>
            <button type="submit">REGISTRATI</button>
        </form>
        <?php if ($errore) { echo "<p>$errore</p>"; } ?>
        <footer>
            Hai già un account? <a href="login.php">Accedi qui</a>
        </footer>
    </div>
</body>
</html>
