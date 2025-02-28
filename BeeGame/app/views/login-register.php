<?php
namespace App\Views;

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . "http://$_SERVER[HTTP_HOST]" . '/Beegame/app/views/select-game.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../config/css/index.css" rel="stylesheet">
    <title>Login / Register</title>
</head>

<body>
    <div class="container">
        <div class="custom-container">
            <form id="myForm">
                <!-- Etichetta per l'input -->
                <label for="username" class="form-label">Inserisci il tuo nome:</label>

                <!-- Campo di input -->
                <input type="text" id="username" name="username" required placeholder="Username" class="form-control">

                <!-- Contenitore per i bottoni -->
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" onclick="submitForm('register')" class="btn btn-custom">Register</button>
                    <button type="button" onclick="submitForm('login')" class="btn btn-custom">Login</button>
                </div>

                <!-- Messaggio di risposta -->
                <div id="message" class="mt-3"></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        async function submitForm(action) {
            const username = document.getElementById('username').value;
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = '';

            if (!username.trim()) {
                messageDiv.innerHTML = '<div class="alert alert-warning">Il campo username è obbligatorio.</div>';
                return;
            }

            const response = await fetch('http://localhost/Beegame/app/controllers/GameRounter.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username: username, action: action })
            })

            const data = await response.json();

            if (data.success) {
                window.location.href = 'select-game.php';
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            }
        }
    </script>
</body>

</html>
