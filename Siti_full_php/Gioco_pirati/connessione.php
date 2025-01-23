<?php

$host = 'localhost';
$dbname = 'gioco_pirati';
$username = 'root';
$password = '';


$conn = new mysqli($host, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}
?>
