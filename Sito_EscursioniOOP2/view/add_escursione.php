<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aggiungi Escursione</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        header {
            background-color: #007BFF;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        main {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        h1 {
            color: #333;
        }
        form label {
            display: block;
            margin: 10px 0 5px;
        }
        form input[type="text"],
        form input[type="number"],
        form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        form button {
            background-color: #007BFF;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <header>
        <?php include 'navbar.php'; ?>
    </header>

    <main>
        <h1>Aggiungi una nuova escursione</h1>

        <?php if (!empty($errore)): ?>
            <p class="error"><?php echo htmlspecialchars($errore); ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="sentiero">Sentiero:</label>
            <input type="text" name="sentiero" id="sentiero" required>

            <label for="durata">Durata (ore):</label>
            <input type="number" name="durata" id="durata" required>

            <label for="difficolta">Difficoltà:</label>
            <input type="text" name="difficolta" id="difficolta" required>

            <label for="punti">Punti:</label>
            <input type="number" name="punti" id="punti" required>

            <label for="foto">Foto:</label>
            <input type="file" name="foto" id="foto" required>

            <button type="submit">Aggiungi Escursione</button>
        </form>
    </main>
</body>
</html>
