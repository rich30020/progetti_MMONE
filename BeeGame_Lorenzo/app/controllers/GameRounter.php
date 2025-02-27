<?php
namespace App\Controllers;

session_start();

require_once dirname(__DIR__) . '/models/BeeType.php';
require_once dirname(__DIR__) . '/models/Bee.php';
require_once dirname(__DIR__) . '/models/Game.php';
require_once dirname(__DIR__) . '/models/Hive.php';
require_once dirname(__DIR__) . '/models/HiveLayout.php';
require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '/controllers/BeeGameController.php';

use App\Models\BeeType;
use App\Models\Game;
use App\Models\User;
use App\Models\Hive;
use App\Models\HiveLayout;
use App\Models\Bee;
use App\Controllers\BeeGameController;

$controller = new BeeGameController();

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo "Nessun dato JSON ricevuto.";
} else if (isset($data['game']) && isset($data['bees'])) {
    //funzione salva gioco
    $game = new Game();
    $game->setId($data['game']['id']);
    $game->setRound($data['game']['round']);
    $game->setLives($data['game']['lives']);
    $user = new User();
    $user->read($data['game']['user_id']);
    $hive = new Hive();
    $hive->read($data['game']['hive_id']);
    $game->setUser($user);
    $game->setHive($hive);

    $bees = [];
    $noalive = true;
    foreach ($data['bees'] as $beeJS) {
        $bee = new Bee();
        $bee->setId($beeJS['id']);
        $bee->setCurrentHealth($beeJS['currentHealth']);
        $beeType = new BeeType();
        $beeType->read($beeJS['beeType']);
        $bee->setBeeType($beeType);
        $bee->setGame($game);
        $bees[] = $bee;
        if ($bee->getCurrentHealth() > 0) {
            $noalive = false;
        }
    }
    if($game->getLives() <= 0) {
        $noalive = true;
    }
    //controlla se ci sono ancora api vive,
    //se non ci sono crea un nuovo round
    if ($noalive) {
        $newRound = $controller->newRound($game, $bees);
        if ($newRound) {
            $game = $newRound['game'];
            $bees = $newRound['bees'];
            if ($controller->saveGame($game, $bees)) {
                $response['game'] = ['id' => $game->getId(), 'round' => $game->getRound(), 'lives' => $game->getLives(), 'user_id' => $game->getUser()->getId(), 'hive_id' => $game->getHive()->getId()];
                foreach ($bees as $bee) {
                    $response['bees'][] = ['id' => $bee->getId(), 'currentHealth' => $bee->getCurrentHealth(), 'beeType' => $bee->getBeeType()->getId(), 'damage' => $bee->getBeeType()->getDamage()];
                }
                $response['success'] = true;
                echo json_encode($response);
            } else {
                echo json_encode('qualcosa non va');
            }
        } else {
            echo json_encode($response['success'] = false);
        }
    } elseif ($controller->saveGame($game, $bees)) {
        $response['game'] = ['id' => $game->getId(), 'round' => $game->getRound(), 'lives' => $game->getLives(), 'user_id' => $game->getUser()->getId(), 'hive_id' => $game->getHive()->getId()];
        foreach ($bees as $bee) {
            $response['bees'][] = ['id' => $bee->getId(), 'currentHealth' => $bee->getCurrentHealth(), 'beeType' => $bee->getBeeType()->getId(), 'damage' => $bee->getBeeType()->getDamage()];
        }
        $response['success'] = true;
        echo json_encode($response);
    } else {
        echo json_encode($response['success'] = false);
    }
} else if (isset($data['action']) && isset($data['username'])) {
    //funzione controllo register e login
    switch ($data['action']) {
        case "register":
            $userName = $data['username'];
            $newUser = $controller->newUser($userName);
            if ($newUser->getId() != -1) {
                $_SESSION['user_id'] = $newUser->getId();
                echo json_encode(['success'=>true, 'message'=>'ok']);
            } else {
                echo json_encode(['success'=>false, 'message'=>'The Username is already registered']);
            }
            break;
        case "login":
            $userName = $data['username'];
            $user = $controller->fetchUserByUsername($userName);
            if ($user->getId() != -1) {
                $_SESSION['user_id'] = $user->getId();
                echo json_encode(['success'=>true, 'message'=>'ok']);
            } else {
                echo json_encode(['success'=>false, 'message'=>'The Username is not registered']);
            }
            break;
    }
}
