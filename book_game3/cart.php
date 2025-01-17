<?php
session_start();

if (!isset($_SESSION['loggato']) || $_SESSION['loggato'] !== true) {
  header("location: login.html");
  exit;
}

// Connessione al database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "book";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connessione fallita: " . $conn->connect_error);
}

// Visualizzazione del carrello
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT books.id, books.name, books.image_url, books.prezzo FROM cart JOIN books ON cart.book_id = books.id WHERE cart.utenti_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Calcola il totale del carrello
$totalPrice = 0;
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cart.css" class="css">
    <link rel="icon" type="image/x-icon" href="images/book.png">
    <title>Carrello</title>
</head>
<body>
    <div class="container">
        <h1>Il tuo Carrello</h1>
        <div class="cart-items">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="cart-item">
                    <img src="<?= $row['image_url'] ?>" alt="<?= $row['name'] ?>" class="cart-item-image">
                    <span class="cart-item-title"><?= $row['name']; ?></span>
                    <span class="cart-price">Prezzo: €<?= number_format($row['prezzo'], 2); ?></span>
                    <form method="post" action="remove_from_cart.php">
                        <input type="hidden" name="book_id" value="<?= $row['id']; ?>">
                        <button type="submit" name="remove_from_cart">Rimuovi</button>
                    </form>
                </div>
                <?php $totalPrice += $row['prezzo']; ?>
            <?php endwhile; ?>
        </div>
        <div class="total-price">
            <h2>Totale Carrello: €<?= number_format($totalPrice, 2); ?></h2>
        </div>
        <a href="area-privata.php" class="backhome">Torna alla libreria</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
