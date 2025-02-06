<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gioco_sherlock_holmes_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connessione fallita: " . $conn->connect_error);
}
?>
