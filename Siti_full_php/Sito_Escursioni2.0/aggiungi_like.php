<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $escursione_id = $_POST['escursione_id'];
    $utente_nome = $_SESSION['nome'];

    // includo la connessione al db
    include 'connessione.php';

    // controlla se l'utente ha già lasciato un non mi piace
    $sql = "SELECT non_mi_piace FROM escursioni WHERE id='$escursione_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['non_mi_piace'] > 0) {
        // rimuovi il non mi piace e aggiungi il mi piace
        $sql = "UPDATE escursioni SET non_mi_piace = non_mi_piace - 1, mi_piace = mi_piace + 1 WHERE id = '$escursione_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: esplora.php");
            exit();
        } else {
            echo "Errore: " . $conn->error;
        }
    } else {
        // aggiungi il mi piace
        $sql = "UPDATE escursioni SET mi_piace = mi_piace + 1 WHERE id = '$escursione_id'";
        if ($conn->query($sql) === TRUE) {
            header("Location: esplora.php");
            exit();
        } else {
            echo "Errore: " . $conn->error;
        }
    }

    $conn->close();
}
?>
