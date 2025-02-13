<?php
require_once __DIR__ . '/../Controller/VotoController.php';

// Ottieni l'ID del commento
$commento_id = isset($_GET['commento_id']) ? (int)$_GET['commento_id'] : 0;

// Istanzia il controller del voto
$votoController = new VotoController();

// Ottieni i voti per il commento
$voti = $votoController->ottieniVoti($commento_id);

// Mostra i voti
if ($voti) {
    echo "Mi Piace: " . $voti['mi_piace'] . "<br>";
    echo "Non Mi Piace: " . $voti['non_mi_piace'] . "<br>";
} else {
    echo "Nessun voto per questo commento.<br>";
}
?>
