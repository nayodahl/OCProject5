<?php
declare(strict_types=1);
// class to connect to database

namespace App\Service;

class Database
{
    protected function dbConnect()
    {
        return new \PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'blog', 'blog', [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
        ]);
    }
}
