<?php
session_start();
if (!isset($_SESSION['nome'])) {
    header("Location: login.html");
    exit();
}

// Includo la connessione al db 
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
                    <h3><?php echo htmlspecialchars($row['sentiero']); ?> <small>by <?php echo htmlspecialchars($row['nome_utente']); ?></small></h3>
                    <p>Durata: <?php echo htmlspecialchars($row['durata']); ?></p>
                    <p>Difficoltà: <?php echo htmlspecialchars($row['difficolta']); ?></p>
                    <p align="justify">Commenti: <?php echo htmlspecialchars($row['commenti']); ?></p>
                    <p>Punti: <?php echo htmlspecialchars($row['punti']); ?></p>
                    <?php if (!empty($row['foto'])): ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['sentiero']); ?>" style="max-width:200px;">
                    <?php endif; ?>
                    <div class="interazioni">
                        <form method="post" action="aggiungi_commento.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <textarea name="commento" placeholder="Aggiungi un commento..." required></textarea>
                            <button type="submit">Commenta</button>
                        </form>
                        <form method="post" action="aggiungi_like.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128077; Mi Piace (<?php echo htmlspecialchars($row['numero_mi_piace']); ?>)</button>
                        </form>
                        <form method="post" action="aggiungi_dislike.php">
                            <input type="hidden" name="escursione_id" value="<?php echo $row['id']; ?>">
                            <button type="submit">&#128078; Non Mi Piace (<?php echo htmlspecialchars($row['numero_non_mi_piace']); ?>)</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $dbInstance->getConnection()->close(); ?>
