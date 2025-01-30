<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_SESSION['nome'];

    // includo la connessione al db
    include 'connessione.php';

    $sentiero = $conn->real_escape_string($_POST['sentiero']);
    $durata = $conn->real_escape_string($_POST['durata']);
    $difficolta = $conn->real_escape_string($_POST['difficolta']);
    $commenti = $conn->real_escape_string($_POST['commenti']);
    $punti = intval($_POST['punti']);
    $foto = $_FILES['foto']['name'];
    $target_dir = "uploads/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["foto"]["name"]);

    if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
        $sql = "SELECT id, punti_escursionistici, livello_esperienza FROM utenti WHERE nome='$nome'";
        $result = $conn->query($sql);
        $utente = $result->fetch_assoc();

        $user_id = $utente['id'];
        $new_punti = $utente['punti_escursionistici'] + $punti;

        // Calcolo del nuovo livello di esperienza
        $nuovo_livello = intval($new_punti / 100);

        $sql = "INSERT INTO escursioni (user_id, sentiero, durata, difficolta, commenti, punti, foto) VALUES ('$user_id', '$sentiero', '$durata', '$difficolta', '$commenti', '$punti', '$foto')";
        if ($conn->query($sql) === TRUE) {
            // Aggiorna i punti e il livello di esperienza dell'utente
            $sql = "UPDATE utenti SET punti_escursionistici='$new_punti', livello_esperienza='$nuovo_livello' WHERE id='$user_id'";
            if ($conn->query($sql) === TRUE) {
                echo "Escursione e punti aggiornati con successo!";
                header("Location: dashboard.php");
            } else {
                echo "Errore nell'aggiornamento dei punti: " . $conn->error;
            }
        } else {
            echo "Errore: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Errore nel caricamento della foto.";
    }

    $conn->close();
}
?>
