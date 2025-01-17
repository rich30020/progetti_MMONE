<?php
// Inizia la sessione e poi la distrugge per fare il logout
session_start();
session_destroy();
// Reindirizza alla pagina di login
header("Location: login.html");
exit();
?>
