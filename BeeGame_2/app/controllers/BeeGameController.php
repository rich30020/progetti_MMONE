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
    // Crea un nuovo utente nel database a partire da una stringa di input e restituisce l'utente
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

    // Crea un nuovo alveare nel database a partire da una stringa di difficoltà e restituisce l'alveare
    public function newHive(string $difficulty): Hive
    {
        $hive = new Hive();
        $id = $hive->create($difficulty);
        $hive->read($id);
        return $hive;
    }

    // Crea un nuovo tipo di ape nel database con i parametri forniti
    public function newBeeType(string $name, int $maxHealth, int $damage, ?string $image): void
    {
        $beeType = new BeeType();
        $beeType->create($name, $maxHealth, $damage);
    }

    // Crea il layout delle api nel database necessario per l'alveare, partendo da un Hive e da una struttura di layout
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

    // Crea un nuovo gioco nel database a partire da un alveare e un utente
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

    // Restituisce un array associativo contenente gli id di tutte le api di un gioco dato
    public function fetchGamesBees(Game $game): array
    {
        $bee = new Bee();
        return $bee->readAllByGameId($game->getId());
    }

    // Restituisce l'utente nel database a partire dal nome utente
    public function fetchUserByUsername(string $userName)
    {
        $user = new User();
        $error = $user->readByUsername($userName);

        if ($error == -1) {
            $user->setId(-1);
            return $user;
        } else {
            return $user;
        }
    }

    // Restituisce un array associativo di tutti i giochi con l'ID utente uguale a quello dell'utente dato
    public function fetchGamesByUser(User $user)
    {
        $game = new Game();
        return $game->readAllByUserId($user->getId());
    }

    // Restituisce tutti gli alveari nel database
    public function fetchAllHives(): array
    {
        $hive = new Hive();
        return $hive->readAllHives();
    }

    // Restituisce un array contenente l'oggetto del gioco, l'ID dell'alveare e l'array degli id delle api
    public function loadGame(Game $game): array
    {
        $hive = new Hive();
        $hive->read($game->getHive()->getId());

        $bee = new Bee();
        $bees = $bee->readAllByGameId($game->getId());

        return ['game' => $game, 'hive' => $hive, 'bees' => $bees];
    }

    // Aggiorna le api con un gioco dato e un array di oggetti api
    // Se l'array di api è vuoto, restituisce false per notificare il fallimento della procedura di salvataggio
    public function saveGame(Game $game, array $bees): bool
    {
        if (empty($bees)) {
            return false;
        } else {
            $game->updateBees($bees);
        }

        $game->updateGame();
        return true;
    }

    // DEPRECATED: Vecchia funzione che verificava lo stato di vittoria nella vecchia versione statica del gioco
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

    // Vecchia funzione che preparava il nuovo round dopo la vittoria nella vecchia versione statica del gioco
    public function newRound(Game $game, array $bees)
    {
        $hive = $game->getHive();
        $hiveLayout = new HiveLayout();
        $hiveLayouts = $hiveLayout->readAllByHiveId($hive->getId());

        // Resetta la salute delle api
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

        // Incrementa il round
        $round = $game->getRound() + 1;
        $game->setRound($round);

        return ['game' => $game, 'bees' => $bees];
    }
}
