<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se è stato inviato il modulo
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    require_once __DIR__ . '/Controller/LoginController.php'; 
    $loginController = new LoginController();
    $errore = $loginController->login($email, $password); // Esegui il login

    // Se login riuscito -> pagina principale
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .circle {
            width: 200px;
            height: 200px;
            background: #007bff;
            position: absolute;
            border-radius: 50%;
            top: -50px;
            left: -50px;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .logo {
            font-size: 50px;
            color: #007bff;
            margin-bottom: 20px;
        }

        h2 {
            color: #2c3e50;
            font-size: 1.5rem;
            margin-bottom: 20px;
        }

        .form input {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1rem;
        }

        .form button {
            width: 100%;
            padding: 15px;
            background: #007bff;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 1.1rem;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .form button:hover {
            background: #0056b3;
            transform: scale(1.05);
        }

        p {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
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
