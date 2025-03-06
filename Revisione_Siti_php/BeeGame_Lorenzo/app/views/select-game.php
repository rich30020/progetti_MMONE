<?php

namespace App\Views;

session_start();

use App\Controllers\BeeGameController;
use App\Models\Hive;
use App\Models\User;
use App\Models\Bee;
use App\Models\Game;

require_once dirname(__DIR__) . '\Models\User.php';
require_once dirname(__DIR__) . '\Models\Game.php';
require_once dirname(__DIR__) . '\Models\Hive.php';
require_once dirname(__DIR__) . '\Controllers\BeeGameController.php';

$controller = new BeeGameController();

$user = new User();
$user->read($_SESSION['user_id']);

if (isset($_POST['hive'])) {
    $hive = new Hive();
    $hive->read($_POST['hive']);
    $controller->newGame($hive, $user);
}

if (isset($_POST['save'])) {
    $game = new Game();
    $game->read($_SESSION['current_game_id']);
    $originalBees = [];
    $bees = [];
    foreach ($_SESSION['originalBees'] as $beeId => $beeCH) {
        $bee = new Bee();
        $bee->read((int)$beeId);
        $originalBees[$beeId] = $bee;
    }
    foreach ($_SESSION['bees'] as $beeId => $beeCH) {
        $bee = new Bee();
        $bee->read((int)$beeId);
        $bee->setCurrentHealth($beeCH);
        $bees[$beeId] = $bee;
    }
    $controller->saveGame($game, $bees);
    foreach ($_SESSION['bees'] as $beeId => $beeCH) {
        unset($_SESSION['bees'][$beeId]);
        unset($_SESSION['originalBees'][$beeId]);
    }
}

$gamesId = $controller->fetchGamesByUser($user);
$games = [];
if ($gamesId != -1){
        foreach ($gamesId as $game) {
        $g = new Game();
        $g->read($game['id']);
        $games[] = $g;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../config/css/index.css" rel="stylesheet">
    <title>Select Game</title>
</head>

<body>
    <div class="container">
        <div class="custom-container">
            <h2>Benvenuto <?php echo $user->getUserName() . " # " . $user->getId() ?> !</h2>
            <a href="../views/create-game.php" class="btn btn-custom"><b>New Game +</b></a>
            <ul class="list-group">
                <?php
                foreach ($games as $game) {
                    echo '<li class="list-group-item">
                                    <span class="game-id">' . $game->getId() . '</span>
                                    <span class="hive-difficulty">' . $game->getHive()->getDifficulty() . ' > Round '. $game->getRound() . ' &nbsp;&nbsp; Lives ' . $game->getLives() .'</span>
                                    <form action="phaser-game.php" method="POST">
                                        <button type="submit" name="game_id" value=' . $game->getId() . ' class="btn btn-action btn-sm btn-custom">
                                            <svg fill="#000000" height="200px" width="200px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 330 330" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <path id="XMLID_222_" d="M250.606,154.389l-150-149.996c-5.857-5.858-15.355-5.858-21.213,0.001 c-5.857,5.858-5.857,15.355,0.001,21.213l139.393,139.39L79.393,304.394c-5.857,5.858-5.857,15.355,0.001,21.213 C82.322,328.536,86.161,330,90,330s7.678-1.464,10.607-4.394l149.999-150.004c2.814-2.813,4.394-6.628,4.394-10.606 C255,161.018,253.42,157.202,250.606,154.389z"></path> </g></svg>
                                        </button>
                                    </form>
                                </li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>