<?php

namespace App\Controllers;

require_once dirname(__DIR__) . '/models/BeeType.php';
require_once dirname(__DIR__) . '/models/Bee.php';
require_once dirname(__DIR__) . '/models/Game.php';
require_once dirname(__DIR__) . '/models/Hive.php';
require_once dirname(__DIR__) . '/models/HiveLayout.php';
require_once dirname(__DIR__) . '/models/User.php';

use App\Models\BeeType;
use App\Models\Game;
use App\Models\User;
use App\Models\Hive;
use App\Models\HiveLayout;
use App\Models\Bee;

class BeeGameController
{
    //creates a new User in the database from an input string and returns the User
    public function newUser(string $name)
    {
        $user = new User();
        $id = $user->create($name);
        if ($id == -1) {
            $user->setId(-1);
            return $user;
        } else {
            $user->read($id);
            return $user;
        }
    }
    //creates a new Hive in the database from an input string and returns the Hive
    public function newHive(string $difficulty): Hive
    {
        $hive = new Hive();
        $id = $hive->create($difficulty);
        $hive->read($id);
        return $hive;
    }
    //creates a new BeeType in the database from inputs: name(string), max health(int), damage taken(int), image link(nullable string)
    public function newBeeType(string $name, int $maxHealth, int $damage, ?string $image): void
    {
        $beeType = new BeeType();
        $beeType->create($name, $maxHealth, $damage, $image);
    }
    //creates the layout of bees in the database necessary for the hive from an input Hive and an array for the structure
    public function newHiveLayout(Hive $hive, array $layout): void
    {
        /*
        Array $Layout Esempio:
            [
                1 => 1,
                2 => 5,
                3 => 8,
            ]
        */

        foreach ($layout as $beeTypeId => $beesCount) {
            $hiveLayout = new HiveLayout();
            $beeType = new BeeType();
            $beeType->read($beeTypeId);
            $hiveLayout->create($beeType, $hive, $beesCount);
        }
    }
    //creates a new Game in the database from a Hive and a User input
    public function newGame(Hive $hive, User $user): Game
    {
        $game = new Game();
        $game->create(0, 3, $user, $hive);
        $hiveLayout = new HiveLayout();
        $hiveLayouts = $hiveLayout->readAllByHiveId($hive->getId());
        foreach ($hiveLayouts as $layoutRow) {
            for ($i = 1; $i <= $layoutRow['bees_count']; $i++) {
                $bee = new Bee();
                $beeType = new BeeType();
                $beeType->read($layoutRow['bee_type_id']);
                $bee->create($beeType, $game);
            }
        }
        return $game;
    }
    //returns an associative array containig of all the Bees' ids from an input Game
    /*
        example return array :
        [
            ['id'] => 1,
            ['id'] => 2,
            ...
        ]
    */
    //this array can then be used to read all the bees' data
    public function fetchGamesBees(Game $game): array
    {
        $bee = new Bee();
        $bees = $bee->readAllByGameId($game->getId());
        return $bees;
    }
    //returns the User in the database from the input userName(string)
    //there can only be a User with a unique username due to database constraints
    //return a user with id = -1 in the case that the input username is not present in the database
    public function fetchUserByUsername(string $userName)
    {
        $user = new User();
        $error = $user -> readByUsername($userName);
        if ($error == -1) {
            $user->setId(-1);
            return $user;
        } else {
            return $user;
        }
    }
    //returns an associative array of all the games with the camp user_id equals to the id of the input User
    public function fetchGamesByUser(User $user)
    {
        $game = new Game();
        $games = $game->readAllByUserId($user->getId());
        return $games;
    }
    //returns all the hives of the database
    public function fetchAllHives(): array
    {
        $hive = new Hive();
        $hives = $hive->readAllHives();
        return $hives;
    }
    //returns an associative array containing the game object, the hive id and the bees' id array
    public function loadGame(Game $game): array
    {
        $hive = new Hive();
        $hive->read($game->getHive()->getId());
        $bee = new Bee();
        $bees = $bee->readAllByGameId($game->getId());
        return['game' => $game, 'hive' => $hive, 'bees' => $bees];
    }
    //updates the bees with an input game and array of bees object
    //if the array of bees return false to notify the failure of the saving procedure
    //when the array of bees is not empty it updates the bees with the game_id equals to the input game's id
    //with the new current health and then it updates the game to the new game state if it mutated
    public function saveGame(Game $game, array $bees): bool
    {
        if(empty($bees)){
            return false;
        }else{
            $game->updateBees($bees);
        }
        $game->updateGame();
        return true;
    }
    //DEPRECATED:
    //old function that checked the vitory state in the old static version of the game
    public function checkVictory(array $bees): bool
    {
        $victory = true;
        foreach ($bees as $bee) {
            if ($bee->getBeeType()->getName() == 'QueenBee' && $bee->getCurrentHealth() > 0) {
                $victory = false;
            }
        }
        return $victory;
    }
    //old function that prepared the new round after victory in the old static version of the game
    public function newRound(Game $game, array $bees)
    {
        $hive=$game->getHive();
        $hiveLayout = new HiveLayout();
        $hiveLayouts = $hiveLayout->readAllByHiveId($hive->getId());
        foreach ($bees as $bee) {
            $bee->setCurrentHealth(0);
        }
        $this->saveGame($game, $bees);
        $bees = [];
        foreach ($hiveLayouts as $layoutRow) {
            for ($i = 1; $i <= $layoutRow['bees_count']; $i++) {
                $bee = new Bee();
                $beeType = new BeeType();
                $beeType->read($layoutRow['bee_type_id']);
                $bee->create($beeType, $game);
                $bees[] = $bee;
            }
        }
        $round = $game->getRound() + 1;
        $game->setRound($round);
        return(['game'=>$game, 'bees'=>$bees]);
    }
}
