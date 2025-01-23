<?php
    // Parametri di connessione al database
    $host = 'localhost';  // Modifica con il tuo host
    $dbname = 'gioco_pirati';  // Sostituisci con il tuo nome database
    $username = 'root';  // Sostituisci con il tuo username MySQL
    $password = '';  // Sostituisci con la tua password MySQL

    try {
        // Crea una connessione PDO al database
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        
        // Imposta l'attributo PDO per l'errore in modalità eccezione
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Se la connessione fallisce, visualizza un messaggio di errore
        die("Connessione al database fallita: " . $e->getMessage());
    }
?>
