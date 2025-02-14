<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Distruggi tutte le variabili di sessione
session_unset();

// Distruggi la sessione
session_destroy();

// Reindirizza alla pagina di login o home
header('Location: login.php');
exit();
?>