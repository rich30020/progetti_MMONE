<?php
session_start();
//session_destroy();
//session_reset();

use App\Controllers\BeeGameController;

require_once '../App/Controllers/BeeGameController.php';

$controller = new BeeGameController();

$auth = false;

if (isset($_SESSION['user_id'])) {
    $auth = true;
} else {
    if(!empty($_POST)){
        $_SESSION['user_id'] = $controller->newUser($_POST['username']);
        $auth = true;
    }
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../config/css/index.css" rel="stylesheet">
    <title>Bee Game WIP</title>
</head>

<body>
    <div class="container">
        <div class="custom-container">
            <a href="../app/views/login-register.php">
                <button class="btn btn-custom">Login / Register</button>
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>