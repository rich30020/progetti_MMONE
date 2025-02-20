<?php
session_start(); 
require_once __DIR__ . '/../Controller/EscursioniController.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "Utente non autenticato"]);
    exit();
}


if (empty($_POST['tipo']) || empty($_POST['commento_id']) || empty($_POST['escursione_id'])) {
    echo json_encode(["success" => false, "message" => "Dati mancanti"]);
    exit();
}

$tipo = $_POST['tipo']; // "like" o "dislike"
$commentoId = intval($_POST['commento_id']);
$escursioneId = intval($_POST['escursione_id']);
$userId = $_SESSION['user']['id'];

$escursioniController = new EscursioniController();


switch ($tipo) {
    case 'like':
        $success = $escursioniController->gestisciMiPiace($commentoId, $userId, true, $escursioneId);
        break;
    case 'dislike':
        $success = $escursioniController->gestisciMiPiace($commentoId, $userId, false, $escursioneId);
        break;
    default:
        echo json_encode(["success" => false, "message" => "Tipo di azione non valido"]);
        exit();
}

if ($success) {
    $likeCount = $escursioniController->getLikeDislikeCount($commentoId, 1);  // 1 per Mi Piace
    $dislikeCount = $escursioniController->getLikeDislikeCount($commentoId, 0);  // 0 per Non Mi Piace

    echo json_encode([
        "success" => true,
        "mi_piace" => $likeCount,
        "non_mi_piace" => $dislikeCount
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Errore nell'aggiornamento"]);
}
?>
