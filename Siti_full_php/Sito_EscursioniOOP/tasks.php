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

class PercorsiEccezionali {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getPercorsi() {
        $sql = "SELECT * FROM percorsi_eccezionali";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Errore nella query: " . $this->conn->error);
        }

        return $result;
    }
}

$dbInstance = Database::getInstance();
$percorsiObj = new PercorsiEccezionali($dbInstance->getConnection());
$percorsi = $percorsiObj->getPercorsi();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Task - Sentieri Proposti</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <h1>Sentieri Proposti</h1>
        <div class="task-grid">
            <?php while ($row = $percorsi->fetch_assoc()): ?>
                <div class="task-item">
                    <h3><?php echo htmlspecialchars($row['sentiero']); ?></h3>
                    <p>Durata: <?php echo htmlspecialchars($row['durata']); ?></p>
                    <p>Difficoltà: <?php echo htmlspecialchars($row['difficolta']); ?></p>
                    <p align="justify">Commenti: <?php echo htmlspecialchars($row['commenti']); ?></p>
                    <?php if (!empty($row['foto'])): ?>
                        <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="<?php echo htmlspecialchars($row['sentiero']); ?>" style="max-width:200px;">
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>
<?php $dbInstance->getConnection()->close(); ?>
