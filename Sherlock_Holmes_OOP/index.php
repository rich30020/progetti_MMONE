<?php
// Classe per gestire il giocatore e i suoi punti
class Giocatore {
    private $conn;
    private $giocatoreId;

    // Costruttore per inizializzare la connessione e l'ID del giocatore
    public function __construct($conn, $giocatoreId) {
        $this->conn = $conn;
        $this->giocatoreId = $giocatoreId;
    }

    // Metodo per ottenere i punti del giocatore
    public function getPunti() {
        $stmt = $this->conn->prepare("SELECT punti FROM giocatori WHERE giocatore_id = ?");
        $stmt->bind_param("i", $this->giocatoreId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $punti = $row['punti'];
        $stmt->close();
        return $punti;
    }
}

// Classe per gestire i casi disponibili nel gioco
class Caso {
    private $conn;

    // Costruttore per inizializzare la connessione
    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Metodo per ottenere tutti i casi
    public function getCasi() {
        $sql = "SELECT caso_id, titolo, descrizione FROM casi ORDER BY caso_id ASC";
        return $this->conn->query($sql);
    }

    // Metodo per calcolare i punti richiesti per ogni caso
    public function calcolaPuntiRichiesti($casoId) {
        return ($casoId - 1) * 20;
    }
}

// Creazione oggetti per la gestione di giocatore e casi
include 'connessione.php';
$giocatore = new Giocatore($conn, 1);
$caso = new Caso($conn);

// Ottenere i punti del giocatore
$punti = $giocatore->getPunti();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Caccia al Colpevole</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="Immagine.png">
</head>
<body>
    <div class="contenitore">
        <h1>Caccia al Colpevole</h1>
        <img class="animated" src="https://assets.culturabologna.it/39dc934c-ba89-406d-8b63-b45b96e6d67d-neri-marcore-in-abiti-di-scena-con-sfondo-quadrata.png/658e92357ddaf83a37526e47323880e7e339b4c0.jpg" alt="Sherlock Holmes">
        
        <h3>Punti attuali: <?php echo $punti; ?></h3>

        <!-- Form per resettare la sessione -->
        <form action="reset_sessione.php" method="post">
            <input type="submit" value="Resetta Sessione">
        </form>

        <?php
        // Ottieni e mostra i casi disponibili
        $result = $caso->getCasi();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $casoId = $row['caso_id'];
                $titolo = $row['titolo'];
                $descrizione = $row['descrizione'];
                $puntiRichiesti = $caso->calcolaPuntiRichiesti($casoId);

                echo "<div class='caso'>";
                echo "<h2>$titolo</h2>";
                echo "<p>$descrizione</p>";

                // Mostra "Indaga" o lucchetto in base ai punti
                if ($punti >= $puntiRichiesti) {
                    echo "<a href='caso.php?caso_id=$casoId'>Indaga</a>";
                } else {
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
