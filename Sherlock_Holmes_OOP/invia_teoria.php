<?php
// Classe per gestire le operazioni relative ai giocatori
class Giocatore {
    private $conn;
    private $giocatoreId;

    public function __construct($conn, $giocatoreId) {
        $this->conn = $conn;
        $this->giocatoreId = $giocatoreId;
    }

    // Metodo per aggiornare i punti del giocatore
    public function aggiornaPunti($punti) {
        $stmt = $this->conn->prepare("UPDATE giocatori SET punti = punti + ? WHERE giocatore_id = ?");
        $stmt->bind_param("ii", $punti, $this->giocatoreId);
        $stmt->execute();
        $stmt->close();
    }

    // Metodo per ottenere i punti del giocatore
    public function ottieniPunti() {
        $stmt = $this->conn->prepare("SELECT punti FROM giocatori WHERE giocatore_id = ?");
        $stmt->bind_param("i", $this->giocatoreId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $punti = $row['punti'];
        $stmt->close();
        return $punti;
    }

    // Metodo per resettare i punti del giocatore
    public function resetPunti() {
        $stmt = $this->conn->prepare("UPDATE giocatori SET punti = 0 WHERE giocatore_id = ?");
        $stmt->bind_param("i", $this->giocatoreId);
        $stmt->execute();
        $stmt->close();
    }
}

// Classe per gestire le risposte dei giocatori
class RispostaGiocatore {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    // Metodo per inserire una risposta del giocatore nel database
    public function inserisciRisposta($casoId, $teoria, $corretto) {
        $stmt = $this->conn->prepare("INSERT INTO risposte_giocatori (caso_id, teoria_giocatore, corretto) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $casoId, $teoria, $corretto);
        return $stmt->execute();
    }
}

// Gestione della richiesta POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    include 'connessione.php';

    $giocatore = new Giocatore($conn, 1);
    $rispostaGiocatore = new RispostaGiocatore($conn);

    $casoId = intval($_POST['caso_id']);
    $teoria = trim($_POST['teoria']);
    $sospettoSelezionato = trim($_POST['sospetto']);
    $sospettoCorretto = trim($_POST['risposta_corretta']);
    $corretto = (strcasecmp($sospettoSelezionato, $sospettoCorretto) === 0) ? 1 : 0;

    // Inserire la risposta nel database
    if ($rispostaGiocatore->inserisciRisposta($casoId, $teoria, $corretto)) {
        if ($corretto) {
            // Aggiungi punti se la risposta è corretta
            $giocatore->aggiornaPunti(20);
            $_SESSION['punti'] += 20;

            // Verifica se il giocatore ha completato l'ultimo caso
            $ultimoCaso = 5;
            if ($casoId == $ultimoCaso) {
                $giocatore->resetPunti(); // Resetta i punti se ha completato l'ultimo caso
                header("Location: vittoria.php");
                exit();
            } else {
                header("Location: vittoria.php");
                exit();
            }
        } else {
            // Vai alla pagina di sconfitta se la risposta è sbagliata
            header("Location: sconfitta.php");
            exit();
        }
    } else {
        echo "<p>Errore: " . $stmt->error . "</p>";
    }

    $conn->close();
} else {
    header('Location: caso.php');
    exit();
}
?>
