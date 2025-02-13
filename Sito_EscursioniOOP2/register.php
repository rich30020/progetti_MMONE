<?php
require_once __DIR__ . '/Controller/registerController.php';

$errore = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $eta = $_POST['eta'];
    $livello_esperienza = $_POST['livello_esperienza'];

    $registerController = new RegisterController();
    $successo = $registerController->register($nome, $email, $password, $eta, $livello_esperienza);

    if ($successo === "Registrazione riuscita!") {
        header('Location: login.php');
        exit();
    } else {
        $errore = $successo;
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Registrazione</title>
</head>
<body>

    <h1>Registrazione</h1>

    <?php if ($errore): ?>
        <p style="color: red;"><?php echo $errore; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Età:</label>
        <input type="number" name="eta" required>

        <label>Livello di esperienza:</label>
        <input type="text" name="livello_esperienza" required>

        <button type="submit">Registrati</button>
    </form>

</body>
</html>
