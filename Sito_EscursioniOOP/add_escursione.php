<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

include 'connessione.php';

class Escursione {
    private $conn;
    private $nome;
    private $sentiero;
    private $durata;
    private $difficolta;
    private $commenti;
    private $punti;
    private $foto;
    private $user_id;

    public function __construct($conn, $nome, $sentiero, $durata, $difficolta, $commenti, $punti, $foto) {
        $this->conn = $conn;
        $this->nome = $nome;
        $this->sentiero = $sentiero;
        $this->durata = $durata;
        $this->difficolta = $difficolta;
        $this->commenti = $commenti;
        $this->punti = $punti;
        $this->foto = $foto;
    }

    public function caricaFoto() {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($this->foto['name']);
        return move_uploaded_file($this->foto["tmp_name"], $target_file);
    }

    public function aggiornaPunti($new_punti) {
        $sql = "SELECT id, punti_escursionistici, livello_esperienza FROM utenti WHERE nome='$this->nome'";
        $result = $this->conn->query($sql);
        $utente = $result->fetch_assoc();
        $this->user_id = $utente['id'];
        $new_punti = $utente['punti_escursionistici'] + $new_punti;
        $nuovo_livello = intval($new_punti / 100);

        $sql = "UPDATE utenti SET punti_escursionistici='$new_punti', livello_esperienza='$nuovo_livello' WHERE id='$this->user_id'";
        $this->conn->query($sql);
        return $new_punti;
    }

    public function aggiungiEscursione() {
        $sql = "INSERT INTO escursioni (user_id, sentiero, durata, difficolta, commenti, punti, foto) 
                VALUES ('$this->user_id', '$this->sentiero', '$this->durata', '$this->difficolta', '$this->commenti', '$this->punti', '$this->foto')";
        return $this->conn->query($sql);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sentiero = $_POST['sentiero'];
    $durata = $_POST['durata'];
    $difficolta = $_POST['difficolta'];
    $commenti = $_POST['commenti'];
    $punti = intval($_POST['punti']);
    $foto = $_FILES['foto'];

    $escursione = new Escursione($conn, $_SESSION['nome'], $sentiero, $durata, $difficolta, $commenti, $punti, $foto);

    if ($escursione->caricaFoto()) {
        $new_punti = $escursione->aggiornaPunti($punti);
        if ($escursione->aggiungiEscursione()) {
            echo "Escursione e punti aggiornati con successo!";
            header("Location: dashboard.php");
        } else {
            echo "Errore nell'aggiornamento dei punti.";
        }
    } else {
        echo "Errore nel caricamento della foto.";
    }
}

$conn->close();
?>
