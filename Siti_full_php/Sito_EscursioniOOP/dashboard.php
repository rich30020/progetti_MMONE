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

class Utente {
    private $conn;
    private $nome;

    public function __construct($conn, $nome) {
        $this->conn = $conn;
        $this->nome = $nome;
    }

    public function getUserInfo() {
        $sql = "SELECT * FROM utenti WHERE nome=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->nome);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}

class Escursione {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAllEscursioni() {
        $sql = "SELECT escursioni.*, utenti.nome AS nome_utente, 
                escursioni.mi_piace AS numero_mi_piace,
                escursioni.non_mi_piace AS numero_non_mi_piace
                FROM escursioni 
                JOIN utenti ON escursioni.user_id = utenti.id";
        return $this->conn->query($sql);
    }
}

$dbInstance = Database::getInstance();
$conn = $dbInstance->getConnection();
$utenteObj = new Utente($conn, $_SESSION['nome']);
$utente = $utenteObj->getUserInfo();
$escursioneObj = new Escursione($conn);
$escursioni = $escursioneObj->getAllEscursioni();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Utente</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/x-icon" href="pngwing.com.png">
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="content">
        <div class="user-info">
            <h1>Benvenuto, <?php echo $utente['nome']; ?>!</h1>
            <p>Livello di esperienza: <?php echo $utente['livello_esperienza']; ?></p>
            <p>Punti escursionistici: <?php echo $utente['punti_escursionistici']; ?></p>
        </div>
        <div class="user-escursioni">
            <h2>Le tue escursioni:</h2>
            <ul>
                <?php while($row = $escursioni->fetch_assoc()): ?>
                    <li>
                        <h3><?php echo $row['sentiero']; ?></h3>
                        <p>Durata: <?php echo $row['durata']; ?></p>
                        <p>Difficoltà: <?php echo $row['difficolta']; ?></p>
                        <p>Commenti: <?php echo $row['commenti']; ?></p>
                        <p>Punti: <?php echo $row['punti']; ?></p>
                        <?php if ($row['foto']): ?>
                            <img id="foto_iniziali" src="uploads/<?php echo $row['foto']; ?>" alt="<?php echo $row['sentiero']; ?>" style="max-width:200px;">
                        <?php endif; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

    <div class="form-container">
        <h2>Aggiungi un nuovo Sentiero</h2>
        <form method="post" action="add_escursione.php" enctype="multipart/form-data">
            <label for="sentiero">Sentiero:</label>
            <input type="text" id="sentiero" name="sentiero" required>
            <br>
            <label for="durata">Durata (HH:MM:SS):</label>
            <input type="text" id="durata" name="durata" required>
            <br>
            <label for="difficolta">Difficoltà:</label>
            <input type="text" id="difficolta" name="difficolta" required>
            <br>
            <label for="commenti">Commenti:</label>
            <textarea id="commenti" name="commenti"></textarea>
            <br>
            <label for="punti">Punti:</label>
            <input type="number" id="punti" name="punti" required>
            <br>
            <label for="foto">Foto del Sentiero:</label>
            <input type="file" id="foto" name="foto">
            <br>
            <input type="submit" value="Aggiungi">
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
