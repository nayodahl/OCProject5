<?php
declare(strict_types=1);

namespace App\Service;

use \PDO;

// class to connect to database
class Database
{
    protected function dbConnect(): PDO
    {
        return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'blog', 'blog', [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
