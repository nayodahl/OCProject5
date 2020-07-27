<?php
declare(strict_types=1);

namespace App\Service;

use \PDO;

// class to connect to database
class Database
{
    protected function dbConnect(): PDO
    {
        $ini = parse_ini_file('C:\xampp\htdocs\OCProject5\src\config.ini');
        $dbName = $ini['db_name'];
        $dbUser = $ini['db_user'];
        $dbPassword = $ini['db_password'];
        
        return new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8", $dbUser,  $dbPassword, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
