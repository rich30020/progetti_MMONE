<?php

namespace App\Models;

use mysqli;

class Game
{
    protected int $id;
    protected int $round;
    protected int $lives;
    protected User $user;
    protected Hive $hive;

    public function getId(): int
    {
        return $this->id;
    }
    public function getRound(): int
    {
        return $this->round;
    }
    public function getLives(): int
    {
        return $this->lives;
    }
    public function getUser(): User
    {
        return $this->user;
    }
    public function getHive(): Hive
    {
        return $this->hive;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setRound(int $round): void
    {
        $this->round = $round;
    }
    public function setLives(int $lives): void
    {
        $this->lives = $lives;
    }
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
    public function setHive(Hive $hive): void
    {
        $this->hive = $hive;
    }

    public function create(int $round, int $lives, User $user, Hive $hive): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $userId = $user->getId();
        $hiveId = $hive->getId();
        $query = "INSERT INTO `games`(`id`, `round`, `lives`, `hive_id`, `user_id`) VALUES (NULL, '$round', '$lives', '$hiveId', '$userId');";
        $this->id = $mysql->insert($query);
        $this->round = $round;
        $this->lives = $lives;
        $this->user = $user;
        $this->hive = $hive;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `games` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $this->setRound($results[0]['round']);
        $this->setLives($results[0]['lives']);
        $user = new User();
        $user->read($results[0]['user_id']);
        $this->setUser($user);
        $hive = new Hive();
        $hive->read($results[0]['hive_id']);
        $this->setHive($hive);
    }
    public function readAllByUserId(int $userId)
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `games` WHERE user_id=$userId";
        $results = $mysql->query($query);
        return $results;
    }
    public function updateBees(array $changedBees): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = '';
        foreach($changedBees as $bee){
            $query .= "UPDATE `bees`
                        SET `current_health`=". $bee->getCurrentHealth() ."
                        WHERE `id`=" .$bee->getId().";";
            $mysql->update($query);
        }
    }
    public function updateGame(): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = '';
        $query .= "UPDATE `games`
                    SET `round`=". $this->round .", `lives`=". $this->lives ."
                    WHERE `id`=".$this->id.";";
        $mysql->update($query);
    }
}
