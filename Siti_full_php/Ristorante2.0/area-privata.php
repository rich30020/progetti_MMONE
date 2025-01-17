<?php include 'connessione_ristorante.php' ?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./images/restaurant.png">
    <title>Gestione Prenotazioni</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="main.css">
    <style>
        .section-container {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .section-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #343a40;
            text-align: center;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <div class="section-container">
            <?php include 'table_map.php'; ?>
        </div>
        
        <div class="section-container">
            <?php include 'form.php'; ?>
        </div>
        
        <div class="section-container">
            <?php include 'prenotazioni.php'; ?>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
