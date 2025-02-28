<?php
namespace App\Views;

use function session_start;
use function header;

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/Beegame/app/views/select-game.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo '../../config/css/index.css'; ?>" rel="stylesheet">
    <title>Login / Register</title>
</head>

<body>
    <div class="container">
        <div class="custom-container">
            <form id="myForm">
                <label for="username" class="form-label">Inserisci il tuo nome:</label>
                <input type="text" id="username" name="username" required placeholder="Username" class="form-control">
                <div class="d-flex justify-content-between mt-3">
                    <button type="button" onclick="submitForm('register')" class="btn btn-custom">Register</button>
                    <button type="button" onclick="submitForm('login')" class="btn btn-custom">Login</button>
                </div>
                <div id="message" class="mt-3"></div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    async function submitForm(action) {
        const username = document.getElementById('username').value.trim();
        const messageDiv = document.getElementById('message');
        messageDiv.innerHTML = '';

        if (!username) {
            messageDiv.innerHTML = '<div class="alert alert-warning">Il campo username è obbligatorio.</div>';
            return;
        }

        const data = { username, action };

        try {
            const response = await fetch('../controllers/GameRounter.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                const jsonResponse = await response.json();

                if (jsonResponse.success) {
                    window.location.href = 'select-game.php';
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${jsonResponse.message}</div>`;
                }
            } else {
                throw new Error("Errore nella risposta del server");
            }
        } catch (error) {
            console.error("Errore nella richiesta:", error);
            messageDiv.innerHTML = '<div class="alert alert-danger">Si è verificato un errore. Riprova più tardi.</div>';
        }
    }
    </script>
</body>

</html>
