<?php
declare(strict_types=1);

namespace App\Service;

class Config
{
    public $contactMail;
    public $serverUrl;
    public $dbName;
    public $dbUser;
    public $dbPassword;
    
    public function __construct()
    {
        if (file_exists('../config.ini')) {
            $ini = parse_ini_file('../config.ini');

            $this->contactMail = $ini['contact_email'];
            $this->serverUrl = $ini['server_url'];
    
            $this->dbName = $ini['db_name'];
            $this->dbUser = $ini['db_user'];
            $this->dbPassword = $ini['db_password'];
        }
    }
}
