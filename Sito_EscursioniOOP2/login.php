<?php
session_start(); // Avvia la sessione se non è già avviata

require_once __DIR__ . '/Controller/loginController.php';

$errore = ''; // Variabile per gli errori

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ottieni i dati dal form
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Crea una nuova istanza del controller
    $loginController = new LoginController();

    // Chiama il metodo login dal controller e ottieni eventuali errori
    $utente = $loginController->login($email, $password);

    if (is_array($utente)) {  // Verifica se il login è riuscito (l'utente sarà un array)
        // Se il login è riuscito, salva i dati dell'utente nella sessione
        $_SESSION['utente_id'] = $utente['id']; // Memorizza l'ID utente
        $_SESSION['email'] = $utente['email'];  // Memorizza l'email dell'utente
        $_SESSION['nome'] = $utente['nome'];    // Memorizza il nome dell'utente
        
        // Reindirizza alla dashboard o alla pagina desiderata
        header("Location: index.php"); 
        exit();
    } else {
        // Se il login fallisce, mostra l'errore
        $errore = $utente; // $utente conterrà la stringa di errore
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/cc/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="main.css">
    <title>Accedi</title>
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <div class="circle"></div>
    <div class="card">
        <div class="logo">
            <i class="bx bx-bitcoin"></i>
        </div>
        <h2>Accedi</h2>
        <form class="form" method="post" action="login.php">
            <input type="email" placeholder="Email" id="email" name="email" required>
            <input type="password" placeholder="Password" id="password" name="password" required>
            <button type="submit">Accedi</button>
            <?php if ($errore): ?>
                <div class="error"><?= $errore ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
