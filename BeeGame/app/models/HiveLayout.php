<?php

namespace App\Models;

require_once 'MySql.php';

class HiveLayout
{
    protected int $id;
    protected Hive $hive;
    protected BeeType $beeType;
    protected int $beesCount;

    public function getId(): int
    {
        return $this->id;
    }
    public function getBeeType(): BeeType
    {
        return $this->beeType;
    }
    public function getHive(): Hive
    {
        return $this->hive;
    }
    public function getBeesCount(): int
    {
        return $this->beesCount;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setBeeType(BeeType $beeType): void
    {
        $this->beeType = $beeType;
    }
    public function setHive(Hive $hive): void
    {
        $this->hive = $hive;
    }
    public function setBeesCount(int $beesCount): void
    {
        $this->beesCount = $beesCount;
    }
    public function create(BeeType $beeType, Hive $hive, int $beesCount): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $hiveId = $hive->getId();
        $beeTypeId = $beeType->getId();
        $query = "INSERT INTO `hive_layouts`(`id`, `hive_id`, `bee_type_id`, `bees_count`) VALUES (NULL, '$hiveId', '$beeTypeId', '$beesCount');";
        $this->id = $mysql->insert($query);
        $this->beeType = $beeType;
        $this->hive = $hive;
        $this->beesCount = $beesCount;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `hive_layouts` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $beeType = new BeeType();
        $beeType->read($results[0]['bee_type_id']);
        $this->setBeeType($beeType);
        $hive = new Hive();
        $hive->read($results[0]['hive_id']);
        $this->setHive($hive);
        $this->setBeesCount($results[0]['bees_count']);
    }
    public function readAllByHiveId(int $hive_id): array
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `hive_layouts` WHERE hive_id=$hive_id";
        $results = $mysql->query($query);
        return $results;
    }
}
