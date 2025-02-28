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

        const data = {
            username: username,
            action: action
        };

        try {
            const response = await fetch('../controllers/GameRounter.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)  
        });


            // Verifica se la risposta è corretta
            if (response.ok) {
                const jsonResponse = await response.json();  // Aspetta la risposta come JSON

                if (jsonResponse.success) {
                    window.location.href = 'select-game.php';  // Redirige alla pagina di selezione gioco
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${jsonResponse.message}</div>`;  // Mostra il messaggio di errore
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
