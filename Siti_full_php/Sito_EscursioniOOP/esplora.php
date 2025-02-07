<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

include 'connessione.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $db = new ConnessioneDB();
        $this->conn = $db->conn;
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

class Esplora {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getEscursioni() {
        $sql = "SELECT escursioni.*, utenti.nome AS nome_utente, 
                escursioni.mi_piace AS numero_mi_piace,
                escursioni.non_mi_piace AS numero_non_mi_piace
                FROM escursioni 
                JOIN utenti ON escursioni.user_id = utenti.id";
        return $this->conn->query($sql);
    }

    public function getCommenti($escursione_id) {
        $sql = "SELECT commenti.commento, utenti.nome AS nome_utente 
                FROM commenti 
                JOIN utenti ON commenti.user_id = utenti.id 
                WHERE commenti.escursione_id = ? 
                ORDER BY commenti.id DESC";  // Ordina i commenti dal più recente
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $escursione_id);
        $stmt->execute();
        return $stmt->get_result();
    }
}

$dbInstance = Database::getInstance();
$esploraObj = new Esplora($dbInstance->getConnection());
$escursioni = $esploraObj->getEscursioni();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esplora Escursioni</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <h1>Esplora Escursioni</h1>
        <div class="escursioni-grid">
            <?php while ($row = $escursioni->fetch_assoc()): ?>
                <div class="escursione-item">
                    <h3><?php echo $row['sentiero']; ?> <small>by <?php echo $row['nome_utente']; ?></small></h3>
                    <p>Durata: <?php echo $row['durata']; ?></p>
                    <p>Difficoltà: <?php echo $row['difficolta']; ?></p>
                    <p align="justify">Commenti: <?php echo $row['commenti']; ?></p>
                    <p>Punti: <?php echo $row['punti']; ?></p>
                    
                    <?php if ($row['foto']): ?>
                        <img src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['sentiero']; ?>" style="max-width:200px;">
                    <?php endif; ?>

                    <!-- Sezione Commenti -->
                    <div class="commenti">
                        <h4>Commenti:</h4>
                        <?php 
                        $commenti = $esploraObj->getCommenti($row['id']);
                        if ($commenti->num_rows > 0): ?>
                            <ul>
                                <?php while ($commento = $commenti->fetch_assoc()): ?>
                                    <li><strong><?php echo $commento['nome_utente']; ?>:</strong> <?php echo $commento['commento']; ?></li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p>Ancora nessun commento. Scrivi il primo!</p>
                        <?php endif; ?>
                    </div>

                    <div class="interazioni">
                        <form method="post" action="aggiungi_commento.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <textarea name="commento" placeholder="Aggiungi un commento..." required></textarea>
                            <button type="submit">Commenta</button>
                        </form>
                        <form method="post" action="aggiungi_like.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128077; Mi Piace (<?php echo $row['numero_mi_piace']; ?>)</button>
                        </form>
                        <form method="post" action="aggiungi_dislike.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128078; Non Mi Piace (<?php echo $row['numero_non_mi_piace']; ?>)</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $dbInstance->getConnection()->close(); ?>
