<?php
session_start();

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/Controller/EscursioniController.php';

// Oggetto per recuperare le escursioni
$escursioniController = new EscursioniController();
$escursioni = $escursioniController->getEscursioni();

function mostraStelle($bellezza) {
    return str_repeat('⭐', $bellezza);
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esplora le Escursioni</title>
    <link rel="stylesheet" href="view/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        /* Header */
        h2 {
            color: #2c3e50;
            font-size: 2rem;
            margin-bottom: 20px;
            font-weight: bold;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.2);
        }

        .container {
            margin-top: 30px;
        }

        /* Cards */
        .card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 18px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            border-radius: 12px 12px 0 0;
            object-fit: cover;
            height: 220px;
        }

        .card-body {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 0 0 12px 12px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .card-title {
            color: #34495e;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 15px;
        }

        .card-text strong {
            color: #2c3e50;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: scale(1.05);
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
            font-size: 1rem;
            padding: 12px 25px;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: scale(1.05);
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
            font-size: 1rem;
            padding: 12px 25px;
            border-radius: 25px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
            transform: scale(1.05);
        }

        /* Responsive Layout */
        @media (max-width: 768px) {
            .container {
                margin-top: 20px;
            }

            .card {
                margin-bottom: 15px;
            }
        }

        /* Animations */
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }

        .container {
            animation: fadeIn 1s ease-in-out;
        }

        /* Footer style */
        footer {
            background-color: #2c3e50;
            color: #ffffff;
            padding: 10px 0;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
<body>
    <?php include 'view/navbar.php'; ?>

    <div class="container mt-5">
        <div class="d-flex justify-content-between mb-3">
            <h2>Benvenuto, <?php echo htmlspecialchars($_SESSION['user']['nome']); ?>!</h2>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>

        <div class="row">
            <?php if (!empty($escursioni)): ?>
                <?php foreach ($escursioni as $escursione): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-lg">
                            <img src="uploads/<?php echo htmlspecialchars($escursione['foto']); ?>" class="card-img-top" alt="Immagine Escursione">
                            <div class="card-body">
                                <h5 class="card-title"> <?php echo htmlspecialchars($escursione['sentiero']); ?> </h5>
                                <p class="card-text">
                                    <strong>Durata:</strong> <?php echo htmlspecialchars($escursione['durata']); ?> ore<br>
                                    <strong>Difficoltà:</strong> <?php echo htmlspecialchars($escursione['difficolta']); ?><br>
                                    <strong>Punti:</strong> <?php echo htmlspecialchars($escursione['punti']); ?><br>
                                    <strong>Bellezza:</strong> <?php echo mostraStelle($escursione['bellezza']); ?>
                                </p>
                                <a href="view/esplora.php?id=<?php echo urlencode($escursione['id']); ?>" class="btn btn-primary">Esplora</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nessuna escursione disponibile.</p>
            <?php endif; ?>
        </div>

        <a href="view/add_escursione.php" class="btn btn-success mt-4">Aggiungi una nuova escursione</a>
    </div>

    <footer>
        <p>&copy; 2025 Escursioni. Tutti i diritti riservati.</p>
    </footer>

</body>
</html>
