<?php

namespace App\Views;

session_start();

use App\Controllers\BeeGameController;
use App\Models\Hive;
use App\Models\User;
use App\Models\Game;
use App\Models\Bee;

require_once dirname(__DIR__) . '\Models\User.php';
require_once dirname(__DIR__) . '\Models\Game.php';
require_once dirname(__DIR__) . '\Models\Hive.php';
require_once dirname(__DIR__) . '\Models\Bee.php';
require_once dirname(__DIR__) . '\Controllers\BeeGameController.php';

$controller = new BeeGameController();
$game = new Game();
$gameState = null;
$originalBees = [];
$bees = [];

if(!empty($_POST['game_id'])){
    $_SESSION['current_game_id']=$_POST['game_id'];
    $game->read($_SESSION['current_game_id']);
    $gameState = $controller->loadGame($game);
    foreach ($gameState['bees'] as $beeId) {
        $bee = new Bee();
        $bee->read($beeId['id']);
        $originalBees[] = $bee;
        if($bee->getCurrentHealth()>0){
            $_SESSION['bees'][$beeId['id']] = $bee->getCurrentHealth();
            $_SESSION['originalBees'][$beeId['id']] = $bee->getCurrentHealth();
        }
    }
    $bees = $originalBees;
}elseif(isset($_POST['new-round'])){
    $game->read($_SESSION['current_game_id']);
    $gameState = $controller->loadGame($game);
    foreach ($gameState['bees'] as $beeId) {
        $bee = new Bee();
        $bee->read($beeId['id']);
        $originalBees[] = $bee;
        if($bee->getCurrentHealth()>0){
            $_SESSION['bees'][$beeId['id']] = $bee->getCurrentHealth();
            $_SESSION['originalBees'][$beeId['id']] = $bee->getCurrentHealth();
        }
    }
    $bees = $originalBees;
}else{
    $game->read($_SESSION['current_game_id']);
    $gameState = $controller->loadGame($game);
    foreach ($_SESSION['originalBees'] as $beeId => $beeCH) {
        $bee = new Bee();
        $bee->read((int)$beeId);
        if($bee->getCurrentHealth()>0){
            $originalBees[$beeId] = $bee;
        }
    }
    foreach ($_SESSION['bees'] as $beeId => $beeCH) {
        $bee = new Bee();
        $bee->read((int)$beeId);
        $bee->setCurrentHealth($beeCH);
        if($bee->getCurrentHealth()>0){
            $bees[$beeId] = $bee;
        }
    }
}

$hive = $gameState['hive'];
$aliveBees = [];

if (isset($_REQUEST['hit'])) {
    if ($_POST['hit'] == 'random') {
        foreach($bees as $index => $bee){
            if ($bee->getCurrentHealth()>0){
                $aliveBees[$index] = $bee;
            }
        }
        $randomBee = array_rand($aliveBees, 1);
        $bees[$randomBee]->getHit();
        $_SESSION['bees'][$bees[$randomBee]->getId()] = $bees[$randomBee]->getCurrentHealth();
    } else {
        foreach ($bees as $bee) {
            if ($bee->getId() == $_POST['hit']) {
                $bee->getHit();
                $_SESSION['bees'][$bee->getId()] = $bee->getCurrentHealth();
            }
        }
    }
}

$victory = false;

if ($controller->checkVictory($bees)) {
    $victory = true;
    $controller->newRound($game, $bees, $originalBees);
    foreach ($_SESSION['originalBees'] as $beeId => $beeCH) {
        if(isset($_SESSION['bees'][$beeId])){
            unset($_SESSION['bees'][$beeId]);
        }
        unset($_SESSION['originalBees'][$beeId]);
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
    <title>Pagina con Griglia</title>
</head>

<body class="game <?php echo $_SESSION['current_game_id']?>">
    <div class="container mt-5">
        <?php if(!$victory): ?>
        <div class='page-header'>
            <h1>Game <?php echo $game->getId() . " Round: " . $game->getRound(); ?></h1>
        </div>
        <div class='game-container mx-auto p-4 w-fit'>
            <div class="bees-container">
                <?php
                foreach ($bees as $bee) {
                    $healthPercent = (int)($bee->getCurrentHealth() / $bee->getBeeType()->getMaxHealth() * 100);
                    echo '<div class="bee-container text-center" ' . ($bee->getCurrentHealth() > 0 ? " " : ' style="display: none"') . '>
                        <!-- Barra della Vita -->
                        <h4>' . $bee->getBeeType()->getName() . '</h4>
                        <div class="progress" style="height: 20px; width: 50%; margin: auto;">
                            <div id="health-bar" class="progress-bar bg-danger" role="progressbar" style="width: ' . $healthPercent . '%;" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100">
                                ' . $healthPercent . '%
                            </div>
                        </div>

                        <br>

                        <!-- Immagine con overlay cliccabile -->
                        <div class="image-container" id="bee' . $bee->getId() . '" onClick="submitBeeForm(' . $bee->getId() . ')">
                            <img src="https://pngimg.com/d/bee_PNG74719.png" alt="Personaggio" class="img-fluid rounded">
                            <div class="overlay">
                                <img src="https://www.pngmart.com/files/7/Scope-PNG-Transparent.png" alt="Scope">
                            </div>
                        </div>

                        <!-- Form nascosto per ogni ape -->
                        <form id="beeForm' . $bee->getId() . '" method="POST" action="game-page.php">
                            <input type="hidden" name="hit" value="' . $bee->getId() . '">
                        </form>
                    </div>';
                }
                ?>
            </div>
            <div class="actions">
                <form id="randomBee" method="POST" action="game-page.php">
                    <button type='submit' class='btn btn-custom' value="random" name='hit'>Random Hit</button>
                </form>
                <form id="saveGame" method="POST" action="select-game.php">
                    <button type='submit' class='btn btn-custom' value="man-save" name='save'>Save Game</button>
                </form>
            </div>
        </div>
        <?php else: ?>
            <div class='game-container mx-auto p-4 w-fit'>
                <form method="POST" action="game-page.php">
                    <button type='submit' class='btn btn-custom' name="new-round">New Round</button>
                </form>
            </div>
        <?php endif ?>
        <script>
            function submitBeeForm(beeId) {
                console.log(beeId);
                document.getElementById(`beeForm${beeId}`).submit();
            }
        </script>

</body>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>