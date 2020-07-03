<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\User;
use \App\Service\Database;
use \PDO;

class UserRepository extends Database
{
    // get total number of Posts
    // return an int
    public function countUsers()
    {
        return (int)current($this->dbConnect()->query("SELECT COUNT(id) from user")->fetch());
    }

    // get Users that have admin or member profile (so we exclude superadmin user), sorted by alphabetical order, with limit and offset as parameters
    // return an array of Users
    public function getNonSuperAdminUsers(int $offset, int $limit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT id AS userId, user.login, user.password, user.email, user.type, user.token, DATE_FORMAT(user.created, \'%d/%m/%Y à %Hh%i\') AS created 
            FROM user 
            WHERE user.type <> "superadmin"
            ORDER BY user.login ASC LIMIT :offset, :usersNumberLimit '
        );
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':usersNumberLimit', $limit, PDO::PARAM_INT);
        $result->execute();
         
        return $result->fetchAll(PDO::FETCH_CLASS, '\App\Model\Entity\User');
    }

    // get Users that have at least admin profile (so including the superadmin), sorted by alphabetical order,
    // return an array of Users
    public function getAllAdminUsers(): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT id AS userId, user.login, user.password, user.email, user.type, user.token, DATE_FORMAT(user.created, \'%d/%m/%Y à %Hh%i\') AS created 
            FROM user 
            WHERE user.type = "admin" OR user.type = "superadmin"
            ORDER BY user.login ASC'
        );
        $result->execute();
         
        return $result->fetchAll(PDO::FETCH_CLASS, '\App\Model\Entity\User');
    }
}
