<?php
declare(strict_types=1);
// class to connect to database

namespace App\Service;

use \PDO;

class Database
{
    protected function dbConnect()
    {
        return new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', 'W6shkqGTGsyM');
    }
}
