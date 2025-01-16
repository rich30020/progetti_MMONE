<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname_kitchen = "kitchen";

$conn_kitchen = new mysqli($servername, $username, $password, $dbname_kitchen);

if ($conn_kitchen->connect_error) {
  die("Connessione fallita: " . $conn_kitchen->connect_error); 
}
?>
