<?php
// Assicurati di includere il file per la connessione al database
require_once __DIR__ . '/../model/ConnessioneDB.php';

// Variabili di errore
$errore = '';

// Controlla se il form è stato inviato
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recupera i dati inviati dal form
    $sentiero = $_POST['sentiero'] ?? '';
    $durata = $_POST['durata'] ?? '';
    $difficolta = $_POST['difficolta'] ?? '';
    $punti = $_POST['punti'] ?? '';
    
    // Gestisci l'upload della foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        // Estrai solo il nome del file, senza percorso
        $foto_nome = basename($_FILES['foto']['name']);
        
        // Salva la foto nella cartella "uploads" (puoi cambiare il percorso se necessario)
        if (!move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../uploads/' . $foto_nome)) {
            $errore = "Errore nel caricamento della foto.";
        }
    } else {
        $errore = "Carica una foto valida.";
    }
    
    // Se non ci sono errori, inserisci i dati nel database
    if (empty($errore)) {
        try {
            // Ottieni la connessione al database
            $conn = ConnessioneDB::getInstance()->getConnessione();

            // Prepara la query di inserimento
            $sql = "INSERT INTO escursioni (sentiero, durata, difficolta, punti, foto) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sssis', $sentiero, $durata, $difficolta, $punti, $foto_nome); // Usa solo il nome del file

            // Esegui la query
            if ($stmt->execute()) {
                header('Location: ../index.php');  // Redirect dopo il successo
                exit;
            } else {
                $errore = "Errore nell'inserimento dell'escursione nel database.";
            }
        } catch (Exception $e) {
            $errore = "Errore nel database: " . $e->getMessage();
        }
    }
}
?>

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
