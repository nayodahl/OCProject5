<?php
declare(strict_types=1);

namespace App\Service;

use \PDO;
use App\Service\Config;

// class to connect to database
class Database
{
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function dbConnect(): PDO
    {
        return new PDO("mysql:host=localhost;dbname=" . $this->config->dbName . ";charset=utf8", $this->config->dbUser, $this->config->dbPassword, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }
}
