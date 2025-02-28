<?php

namespace Config;

class DBConfig{
    protected string $serverName;
    protected string $userName;
    //protected string $userPassword;
    protected string $dbName;

    public function __construct() {
        $this -> serverName = "localhost";
        $this -> userName = "root";
        //$this -> password = ;
        $this -> dbName = "bee_game";
    }
}
?>