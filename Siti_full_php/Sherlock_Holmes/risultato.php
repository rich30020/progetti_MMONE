<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="contenitore">
        <?php
        include 'connessione.php';
        $casoId = $_GET['caso_id'];
        $sospettoSelezionato = urldecode($_GET['sospetto']);


        

        // Estrai solo il nome del sospetto selezionato
        $sospettoSelezionatoNome = explode(' ', $sospettoSelezionato, 3)[0] . ' ' . explode(' ', $sospettoSelezionato, 3)[1];

        $sql = "SELECT risposta_corretta, motivazione_risposta FROM casi WHERE caso_id = $casoId";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $rispostaCorretta = trim($row['risposta_corretta']);

            $corretto = ($sospettoSelezionatoNome === $rispostaCorretta) ? 1 : 0;

            if ($corretto) {
                echo "<h2>Risposta Corretta!</h2>";
            } else {
                echo "<h2>Risposta Errata!</h2>";
            }
            echo "<p>" . $row['motivazione_risposta'] . "</p>";
            echo "<br><a href='index.php'>Torna alla pagina iniziale</a>";
        } else {
            echo "<p>Dettagli del caso non disponibili.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
