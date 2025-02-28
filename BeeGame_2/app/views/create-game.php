<?php
namespace App\Views;

session_start();

use App\Controllers\BeeGameController;
use App\Models\Hive;
use App\Models\User;
use App\Models\Game;

require_once dirname(__DIR__) . '\Models\User.php';
require_once dirname(__DIR__) . '\Models\Game.php';
require_once dirname(__DIR__) . '\Models\Hive.php';
require_once dirname(__DIR__) . '\Controllers\BeeGameController.php';

$controller = new BeeGameController();
$user = new User();
$user->read($_SESSION['user_id']);

$hives = $controller->fetchAllHives();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../config/css/index.css" rel="stylesheet">
    <title>Create Game</title>
</head>

<body>
    <div class="container">
        <div class="custom-container">

            <form id="myForm" action="select-game.php" method="POST">
                <label class="form-label">Choose A Hive:</label>
                <?php
                foreach ($hives as $hive) {
                    echo "<div class='form-check mb-3'>
                            <input class='form-check-input' type='radio' value='" . $hive['id'] . "' name='hive' id='hive-" . $hive['id'] . "'>
                            <label class='form-check-label' for='hive-" . $hive['id'] . "'>" . $hive['difficulty'] . "</label>
                        </div>";
                }
                ?>
                <button value="create-game" type="submit" name="btn_submit"
                    class="btn btn-custom">Create Game</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>