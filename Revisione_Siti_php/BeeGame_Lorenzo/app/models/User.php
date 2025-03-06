<?php

namespace App\Models;

require_once 'MySql.php';

class User
{
    protected int $id;
    protected string $userName;

    public function getId(): int
    {
        return $this->id;
    }
    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    public function create(string $userName)
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "INSERT INTO `users`(`id`, `user_name`) VALUES (NULL, '$userName');";
        $id = $mysql->insert($query);
        if($id == -1) {
            return -1;
        } else {
            $this->userName = $userName;
            return $this->id;
        }
    }
    public function read(int $id): void
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `users` WHERE id=$id";
        $results = $mysql->query($query);
        $this->setUserName($results[0]['user_name']);
        $this->setId($results[0]['id']);
    }
    public function readByUsername(string $userName)
    {
        $mysql = new MySql();
        $mysql->dbConnect();
        $query = "SELECT * FROM `users` WHERE user_name='$userName'";
        $results = $mysql->query($query);
        if ($results == -1) {
            return -1;
        } else {
            $this->setUserName($results[0]['user_name']);
            $this->setId($results[0]['id']);
        }
    }
}
