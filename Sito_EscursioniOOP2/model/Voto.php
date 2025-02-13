<?php
require_once __DIR__ . '/../Model/ConnessioneDB.php'; // Includi il file ConnessioneDB.php

class Voto {

    // Metodo per aggiungere un voto
    public function aggiungiVoto($user_id, $commento_id, $voto, $escursione_db) {
        // Verifica se il commento esiste nel database prima di inserire il voto
        if ($commento_id <= 0 || $escursione_db <= 0) {
            throw new Exception("ID del commento o escursione non validi.");
        }

        // Connessione al database tramite Singleton
        $conn = ConnessioneDB::getInstance();

        // Verifica se il commento con l'ID esiste
        $sql = "SELECT id FROM commenti WHERE id = :commento_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':commento_id', $commento_id, PDO::PARAM_INT);
        $stmt->execute();

        // Se non viene trovato alcun commento, lancia un'eccezione
        if ($stmt->rowCount() == 0) {
            throw new Exception("Il commento con ID {$commento_id} non esiste.");
        }

        // Verifica se l'escursione è valida
        $sql = "SELECT id FROM escursioni WHERE id = :escursione_db";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':escursione_db', $escursione_db, PDO::PARAM_INT);
        $stmt->execute();

        // Se non viene trovata alcuna escursione, lancia un'eccezione
        if ($stmt->rowCount() == 0) {
            throw new Exception("L'escursione con ID {$escursione_db} non esiste.");
        }

        // Query SQL per inserire il voto
        $sql = "INSERT INTO voti (user_id, commento_id, voto, escursione_db) 
                VALUES (:user_id, :commento_id, :voto, :escursione_db)";
        
        // Prepara la query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':commento_id', $commento_id, PDO::PARAM_INT);
        $stmt->bindParam(':voto', $voto, PDO::PARAM_INT);
        $stmt->bindParam(':escursione_db', $escursione_db, PDO::PARAM_INT);

        // Esegui la query
        $stmt->execute();
    }

    // Metodo per ottenere il conteggio dei Mi Piace e Non Mi Piace
    public function getLikeDislikeCount($commento_id, $voto_type) {
        $conn = ConnessioneDB::getInstance();

        // Query SQL per ottenere i conteggi
        $sql = "SELECT 
                    SUM(CASE WHEN voto = 1 THEN 1 ELSE 0 END) AS mi_piace, 
                    SUM(CASE WHEN voto = -1 THEN 1 ELSE 0 END) AS non_mi_piace
                FROM voti 
                WHERE commento_id = :commento_id AND voto = :voto_type";
        
        // Prepara la query
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':commento_id', $commento_id, PDO::PARAM_INT);
        $stmt->bindParam(':voto_type', $voto_type, PDO::PARAM_INT);

        // Esegui la query
        $stmt->execute();

        // Ottieni il risultato e restituiscilo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
