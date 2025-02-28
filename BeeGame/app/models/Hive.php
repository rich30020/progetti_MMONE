<?php

namespace App\Models;

class Hive
{

    //ATTRIBUTI
    protected int $id;
    protected string $difficulty;

    //GETTERS
    public function getId(): int
    {
        return $this->id;
    }
    public function getDifficulty(): string
    {
        return $this->difficulty;
    }

    //SETTERS
    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setDifficulty(string $difficulty): void
    {
        $this->difficulty = $difficulty;
    }

    public function create(string $difficulty): int
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "INSERT INTO `hives`(`id`, `difficulty`) VALUES (NULL, '$difficulty');";
        $this->id = $mysql->insert($query);
        $this->difficulty = $difficulty;
        return $this->id;
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `hives` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setId($results[0]['id']);
        $this->setDifficulty($results[0]['difficulty']);
    }
    public function readAllHives()
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `hives`";
        $results = $mysql->query($query);
        return $results;
    }

    /*
        public function __construct(int $id, int $QueenBees = 1, int $WorkerBees = 5, int $DroneBees = 8){
            $this->id = $id;

            for($i = 0; $i < $QueenBees; $i++)
            {
                $this->hive[] = new Bee("QueenBee");
            }
            for($i = 0; $i < $WorkerBees; $i++)
            {
                $this->hive[] = new Bee("WorkerBee");
            }
            for($i = 0; $i < $DroneBees; $i++)
            {
                $this->hive[] = new Bee("DroneBee");
            }

            shuffle($this->hive);
        }

        public function check_death():void{
            foreach($this->hive as $i => $bee){
                if($bee->get_hp() <= 0){
                    array_splice($this->hive, $i, 1);
                }
            }
        }

        public function check_victory():bool{
            $victory = true;
            foreach($this->hive as $bee){
                if($bee->get_type() === "QueenBee"){
                    $victory = false;
                }
            }

            return $victory;
        }
        */
}
