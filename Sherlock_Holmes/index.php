<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caccia al Colpevole</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="contenitore">
        <h1>Caccia al Colpevole</h1>
        <img class="animated" src="https://assets.culturabologna.it/39dc934c-ba89-406d-8b63-b45b96e6d67d-neri-marcore-in-abiti-di-scena-con-sfondo-quadrata.png/658e92357ddaf83a37526e47323880e7e339b4c0.jpg" alt="Sherlock Holmes">
        
        <?php
        include 'connessione.php';

        $giocatoreId = 1;

        $stmt = $conn->prepare("SELECT punti FROM giocatori WHERE giocatore_id = ?");
        $stmt->bind_param("i", $giocatoreId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $punti = $row['punti'];
        $stmt->close();

        echo "<h3>Punti attuali: $punti</h3>";
        ?>

        <!-- Form per resettare la sessione -->
        <form action="reset_sessione.php" method="post">
            <input type="submit" value="Resetta Sessione">
        </form>

        <?php
        // Ottieni i casi dal database
        $sql = "SELECT caso_id, titolo, descrizione FROM casi ORDER BY caso_id ASC";
        $result = $conn->query($sql);

        // Mostra i casi disponibili
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $casoId = $row['caso_id'];
                $titolo = $row['titolo'];
                $descrizione = $row['descrizione'];

                // Calcola i punti necessari per accedere al caso
                $puntiRichiesti = ($casoId - 1) * 20;

                echo "<div class='caso'>";
                echo "<h2>$titolo</h2>";
                echo "<p>$descrizione</p>";

                // Mostra il pulsante "Indaga" se il giocatore ha abbastanza punti
                if ($punti >= $puntiRichiesti) {
                    echo "<a href='caso.php?caso_id=$casoId'>Indaga</a>";
                } else {
                    // Altrimenti, mostra un lucchetto
                    echo "<div class='locked' data-tooltip='Punti richiesti: $puntiRichiesti'>";
                    echo "<span class='lucchetto'>&#x1F512;</span>";
                    echo "</div>";
                }

                echo "</div>";
            }
        } else {
            echo "<p>Nessun caso disponibile.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
