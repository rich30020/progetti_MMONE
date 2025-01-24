<?php
session_start();
include 'connessione.php';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="Immagine.png">
    <title>Sherlock Holmes - Caso</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Stili principali */
        body {
            font-family: 'Raleway', sans-serif;
            background: linear-gradient(to bottom, #1b2631, #34495e);
            color: #ecf0f1;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            text-align: center;
        }

        .contenitore {
            background: rgba(44, 62, 80, 0.95);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            padding: 30px;
            max-width: 700px;
            width: 90%;
            animation: fadeIn 1s ease-in-out;
            text-align: left;
        }

        h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #f39c12;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 1.1rem;
            color: #bdc3c7;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        h3 {
            font-size: 1.4rem;
            color: #ecf0f1;
            margin-bottom: 10px;
        }

        .sospetti-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .sospetto-item {
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 10px 15px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            transition: background 0.3s ease;
        }

        .sospetto-item:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        textarea {
            width: 100%;
            height: 80px;
            font-size: 1rem;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #34495e;
            resize: none;
            transition: border-color 0.3s ease;
        }

        textarea:focus {
            border-color: #f39c12;
            outline: none;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            font-family: 'Inter', sans-serif;
            color: #34495e;
            border: 2px solid #34495e;
            border-radius: 8px;
            margin-bottom: 10px;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #f39c12;
            outline: none;
        }

        .hint {
            color: #bdc3c7;
            font-size: 0.9rem;
            font-style: italic;
            margin-bottom: 20px;
        }

        input[type="submit"] {
            padding: 12px 20px;
            font-size: 1.1rem;
            font-family: 'Inter', sans-serif;
            color: #fff;
            background: linear-gradient(to right, #e74c3c, #c0392b);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        input[type="submit"]:hover {
            background: linear-gradient(to right, #c0392b, #e74c3c);
            transform: scale(1.05);
        }

        img.animated {
            width: 100%;
            max-width: 500px;
            border-radius: 10px;
            margin: 20px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
            animation: bounce 3s infinite;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 600px) {
            h2 {
                font-size: 1.8rem;
            }

            p {
                font-size: 1rem;
            }

            textarea {
                height: 60px;
            }

            input[type="submit"] {
                font-size: 1rem;
                padding: 10px;
            }

            input[type="text"] {
                font-size: 1rem;
                padding: 10px;
            }

            .hint {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="contenitore">
        <h2>Sherlock Holmes - Caso</h2>
        <img class="animated" src="https://i5.walmartimages.com/seo/Plus-Size-Sherlock-Holmes-Costume-for-Men_0092c027-8128-41b2-8687-23d75c896734_2.286d0db44d82139ed39317e707cb9841.png?odnHeight=768&odnWidth=768&odnBg=FFFFFF" alt="Caso">
        <form action="reset_sessione.php" method="post">
            <input type="submit" value="Resetta Sessione">
        </form>
        <?php
        // Ottieni l'ID del caso dalla query string
        $casoId = isset($_GET['caso_id']) ? intval($_GET['caso_id']) : 0;

        // Query per recuperare i dati del caso
        $sql = "SELECT titolo, dettagli, sospetti, risposta_corretta FROM casi WHERE caso_id = $casoId";
        $result = $conn->query($sql);

        // Se ci sono risultati, mostra i dettagli del caso
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            ?>
            <h2><?php echo htmlspecialchars($row['titolo']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($row['dettagli'])); ?></p>
            <h3>Sospetti:</h3>
            <div class="sospetti-form">
                <?php
                // Mostra i sospetti
                $sospetti = explode('|', $row['sospetti']);
                foreach ($sospetti as $sospetto) {
                    $sospetto = trim($sospetto);
                    ?>
                    <div class="sospetto-item">
                        <?php echo htmlspecialchars($sospetto); ?>
                    </div>
                    <?php
                }
                ?>
            </div>
            <form class="sospetti-form" action="invia_teoria.php" method="post">
                <h3>Inserisci il nome del sospetto:</h3>
                <input type="text" name="sospetto" required>
                <p class="hint">⚠ Attenzione: assicurati di scrivere il nome del sospetto esattamente come appare nel testo dei sospetti. Esempi: La Governante, La signora Elizabeth, il socio in affari, il signor Miller, Un vicino di casa sospetto, L'assistente, il signor Clark.</p>
                <h3>La tua teoria:</h3>
                <textarea name="teoria" required></textarea><br>
                <input type="hidden" name="caso_id" value="<?php echo $casoId; ?>">
                <input type="hidden" name="risposta_corretta" value="<?php echo htmlspecialchars(trim($row['risposta_corretta'])); ?>">
                <input type="submit" value="Invia">
            </form>
            <?php
        } else {
            echo "<p>Dettagli del caso non disponibili.</p>";
        }


        $conn->close();
        ?>
    </div>
</body>
</html>
