<?php

namespace App\Models;

class Bee
{
    protected int $id;
    protected int $currentHealth;
    protected BeeType $beeType;
    protected Game $game;

    // Funzione per connettersi al DB e restituire l'oggetto MySql
    private function dbConnect(): MySql
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        return $mysql;
    }

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
        $mysql = $this->dbConnect();  // Usato il metodo dbConnect() per evitare ripetizioni
        $beeTypeId = $beeType->getId();
        $maxHealth = $beeType->getMaxHealth();
        $gameId = $game->getId();
        $query = "INSERT INTO `bees`(`id`, `current_health`, `bee_type_id`, `game_id`) VALUES (NULL, '$maxHealth', '$beeTypeId', '$gameId');";
        $this->id = $mysql->insert($query);
        $this->currentHealth = $maxHealth;
        $this->beeType = $beeType;
        $this->game = $game;
        return $this->id;
    }

    public function read(int $id): void
    {
        $mysql = $this->dbConnect();  // Usato il metodo dbConnect() per evitare ripetizioni
        $query = "SELECT * FROM `bees` WHERE id=$id";
        $results = $mysql->query($query);

        if (empty($results)) {
            return; // In caso di nessun risultato, non fare nulla
        }

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
        $mysql = $this->dbConnect();  // Usato il metodo dbConnect() per evitare ripetizioni
        $query = "SELECT id FROM `bees` WHERE game_id=$gameId;";
        return $mysql->query($query);
    }

    public function getHit(): void
    {
        $this->currentHealth -= $this->beeType->getDamage();  // Scrittura semplificata
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id,
            "currentHealth" => $this->currentHealth,
            "beeType" => $this->beeType->getId(),
            "damage" => $this->beeType->getDamage(),
        ];
    }
}
