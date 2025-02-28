<?php

namespace App\Models;

class BeeType
{
    protected int $id;
    protected string $name;
    protected int $maxHealth;
    protected int $damage;

    public function getId(): int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getMaxHealth(): int
    {
        return $this->maxHealth;
    }
    public function getDamage(): int
    {
        return $this->damage;
    }
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setMaxHealth(int $maxHealth): void
    {
        $this->maxHealth = $maxHealth;
    }
    public function setDamage(int $damage): void
    {
        $this->damage = $damage;
    }

    public function create(string $name, int $maxHealth, int $damage): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "INSERT INTO `bee_types`(`id`, `name`, `max_health`, `damage`, `image`) VALUES (NULL, '$name', '$maxHealth', '$damage');";
        $this->id = $mysql->insert ($query);
        $this->name = $name;
        $this->maxHealth = $maxHealth;
        $this->damage = $damage;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `bee_types` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $this->setName($results[0]['name']);
        $this->setMaxHealth($results[0]['max_health']);
        $this->setDamage($results[0]['damage']);
    }
}
