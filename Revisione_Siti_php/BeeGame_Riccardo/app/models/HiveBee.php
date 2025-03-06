<?php

namespace App\Models;

class HiveBee
{
    protected int $id;
    protected Bee $bee;
    protected BeeType $beeType;
    protected Hive $hive;

    public function getId(): int
    {
        return $this->id;
    }
    public function getBee(): Bee
    {
        return $this->bee;
    }
    public function getBeeType(): BeeType
    {
        return $this->beeType;
    }
    public function getHive(): Hive
    {
        return $this->hive;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setBee(Bee $bee): void
    {
        $this->bee = $bee;
    }
    public function setBeeType(BeeType $beeType): void
    {
        $this->beeType = $beeType;
    }
    public function setHive(Hive $hive): void
    {
        $this->hive = $hive;
    }

    public function create(Bee $bee, BeeType $beeType, Hive $hive): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $beeId = $bee->getId();
        $beeTypeId = $beeType->getId();
        $hiveId = $hive->getId();
        $query = "INSERT INTO `hive_bees`(`id`, `hive_id`, `bee_type_id`, `bee_id`) VALUES (NULL, $hiveId, $beeTypeId, $beeId);";
        $this->id = $mysql->insert($query);
        $this->bee = $bee;
        $this->beeType = $beeType;
        $this->hive = $hive;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `hive_bees` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $bee = new Bee();
        $bee->read($results[0]['bee_id']);
        $this->setBee($bee);
        $beeType = new BeeType();
        $beeType->read($results[0]['bee_type_id']);
        $this->setBeeType($beeType);
        $hive = new Hive();
        $hive->read($results[0]['hive_id']);
        $this->setHive($hive);
    }
}
