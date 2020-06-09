<?php
declare(strict_types=1);
// class pour gérer la connection à la base de donnée

namespace App\Service;

use \PDO;

class Database
{
    protected $database;

    protected function dbConnect()
    {
        $database = new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'W6shkqGTGsyM');
        return $database;
    }
}
