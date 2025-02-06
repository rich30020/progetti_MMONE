<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST['nome'];
        $eta = $_POST['eta'];
        $livello_esperienza = $_POST['livello_esperienza'];

        //Connesione al database
        include 'connessione.php';

        $sql = "INSERT INTO utenti (nome, eta, livello_esperienza) VALUES ('$nome', '$eta', '$livello_esperienza')";

        if ($conn->query($sql) === TRUE) {
            echo "Registrazione avvenuta con successo!";
        } else {
            echo "Errore: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }