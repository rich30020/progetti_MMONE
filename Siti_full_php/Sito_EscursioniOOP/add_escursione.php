<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}


require_once 'connessione.php';

class Escursione {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function aggiungiEscursione($user_id, $sentiero, $durata, $difficolta, $commenti, $punti, $foto) {
        $sql = "INSERT INTO escursioni (user_id, sentiero, durata, difficolta, commenti, punti, foto) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issssis", $user_id, $sentiero, $durata, $difficolta, $commenti, $punti, $foto);
        return $stmt->execute();
    }
}

// Recupera la connessione al database
$db = new ConnessioneDB();
$conn = $db->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_SESSION['nome'];

    // Recupera l'ID utente e i punti attuali
    $sql = "SELECT id, punti_escursionistici FROM utenti WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();
    $utente = $result->fetch_assoc();

    if (!$utente) {
        echo "Errore: utente non trovato.";
        exit();
    }

    $user_id = $utente['id'];
    $punti_attuali = $utente['punti_escursionistici'];

    // Recupera i dati dal form
    $sentiero = $_POST['sentiero'];
    $durata = $_POST['durata'];
    $difficolta = $_POST['difficolta'];
    $commenti = $_POST['commenti'];
    $punti = intval($_POST['punti']);

    // Gestione upload foto
    $foto = "";
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $foto_temp = $_FILES['foto']['tmp_name'];
        $foto_nome = basename($_FILES['foto']['name']);
        $foto_path = $target_dir . $foto_nome;
        $imageFileType = strtolower(pathinfo($foto_path, PATHINFO_EXTENSION));

        $formati_consentiti = ["jpg", "jpeg", "png", "gif"];
        if (in_array($imageFileType, $formati_consentiti)) {
            if (move_uploaded_file($foto_temp, $foto_path)) {
                $foto = $foto_nome;
            } else {
                echo "Errore nel caricamento dell'immagine.";
                exit();
            }
        } else {
            echo "Formato immagine non supportato.";
            exit();
        }
    }

    // Aggiunge la nuova escursione al database
    $escursioneObj = new Escursione($conn);
    if ($escursioneObj->aggiungiEscursione($user_id, $sentiero, $durata, $difficolta, $commenti, $punti, $foto)) {
        // Aggiorna i punti dell'utente
        $nuovi_punti = $punti_attuali + $punti;
        $sql = "UPDATE utenti SET punti_escursionistici = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $nuovi_punti, $user_id);
        $stmt->execute();

        header("Location: dashboard.php?success=1");
        exit();
    } else {
        echo "Errore nell'aggiunta dell'escursione.";
    }
}
?>
