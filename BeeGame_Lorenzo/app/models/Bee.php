<?php

namespace App\Models;

class Bee
{

    protected int $id;
    protected int $currentHealth;
    protected BeeType $beeType;
    protected Game $game;

    public function getId(): int
    {
        return $this->id;
    }
    public function getCurrentHealth(): int
    {
        return $this->currentHealth;
    }
    public function getBeeType(): BeeType
    {
        return $this->beeType;
    }
    public function getGame(): Game
    {
        return $this->game;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setCurrentHealth(int $currentHealth): void
    {
        $this->currentHealth = $currentHealth;
    }
    public function setBeeType(BeeType $beeType): void
    {
        $this->beeType = $beeType;
    }
    public function setGame(Game $game): void
    {
        $this->game = $game;
    }

    public function create(BeeType $beeType, Game $game): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $beeTypeId = $beeType->getId();
        $maxHealth = $beeType->getMaxHealth();
        $gameId = $game->getId();
        $query = "INSERT INTO `bees`(`id`, `current_health`, `bee_type_id`, `game_id`) VALUES (NULL, '$maxHealth', '$beeTypeId', '$gameId');";
        $this->id = $mysql->insert ($query);
        $this->currentHealth = $maxHealth;
        $this->beeType = $beeType;
        $this->game = $game;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `bees` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $this->setCurrentHealth($results[0]['current_health']);
        $beeType = new BeeType();
        $beeType->read($results[0]['bee_type_id']);
        $this->setBeeType($beeType);
        $game = new Game();
        $game->read($results[0]['game_id']);
        $this->setGame($game);
    }

    public function readAllByGameId(int $gameId): array
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT id
            FROM `bees`
            WHERE game_id=$gameId;";
        $results = $mysql->query($query);
        return $results;
    }

    public function getHit(){
        $newHealth = $this->currentHealth - $this->beeType->getDamage();
        $this->currentHealth = $newHealth;
    }

    public function toArray(){
        return [
            "id"=>$this->id,
            "currentHealth"=>$this->currentHealth,
            "beeType"=>$this->beeType->getId(),
            "damage"=>$this->beeType->getDamage(),
        ];
    }
    /*public function __construct(string $type){
            $this->type = BeeHive::$types[$type]["type"];
            $this->hp = BeeHive::$types[$type]["hp"];
            $this->damage = BeeHive::$types[$type]["damage"];
        }

        public function hit():void{
            $this->hp -= $this->damage;
        }
        public function get_type():string{
            return $this->type;
        }
        public function get_hp():int{
            return $this->hp;
        }
        public function get_max_hp():int{
            return BeeHive::$types[$this->type]["hp"];
        }*/
}
