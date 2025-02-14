<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se è stato inviato il modulo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ottieni i dati dal modulo
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Include il file di connessione al DB (Assicurati che il percorso sia corretto)
    require_once __DIR__ . '/Controller/LoginController.php'; // Usa il controller di login
    $loginController = new LoginController();
    $errore = $loginController->login($email, $password); // Esegui il login

    // Se il login è riuscito, reindirizza al principale
    if (!$errore) {
        header('Location: index.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="view/main.css">
</head>
<body>
    <div class="circle"></div>
    <div class="card">
        <div class="logo">
            <i class="bx bx-bitcoin"></i>
        </div>
        <h2>Login</h2>
        <form class="form" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">LOGIN</button>
        </form>
        <?php if (isset($errore)) { echo "<p>$errore</p>"; } ?>
    </div>
</body>
</html>
