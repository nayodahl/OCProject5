<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\User;
use \App\Service\Database;
use \PDO;

class UserRepository extends Database
{
    //get User from its id
    //return User
    public function getUser(int $userId): User
    {
        $result = $this->dbConnect()->prepare(
            'SELECT user.id AS userId, user.login, user.password, user.email, user.type, user.activated, user.token, DATE_FORMAT(user.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(user.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate 
            FROM user
            WHERE user.id = :userId'
        );
        $result->bindValue(':userId', $userId, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchObject(User::class);
    }
    
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
         
        return $result->fetchAll(PDO::FETCH_CLASS, User::class);
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
         
        return $result->fetchAll(PDO::FETCH_CLASS, User::class);
    }

    // give or remove admin rights to one User, so updating its "type" attribute
    // return true if updated successfully
    public function updateUserType(int $userId, string $type): bool
    {
        $result = $this->dbConnect()->prepare('UPDATE user SET user.type = :userType, last_update=NOW() WHERE user.id = :userId');
        $result->bindValue(':userId', $userId, PDO::PARAM_INT);
        $result->bindValue(':userType', $type, PDO::PARAM_STR);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };
        
        return false;
    }

    // check if password is correct for a given User
    // Return a User is true, else null
    public function checkLogin(string $login, string $password): ?User
    {
        $result = $this->dbConnect()->prepare(
            'SELECT id AS userId, user.login, user.password, user.email, user.type, user.token, DATE_FORMAT(user.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(user.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate
            from user WHERE user.login = :userlogin'
        );
        $result->bindValue(':userlogin', $login, PDO::PARAM_STR);
        $result->execute();
        $user = $result->fetchObject(User::class);
        if ($user === false) {
            return null;
        }
        if (password_verify($password, $user->getPassword())) {
            return $user;
        }

        return null;
    }

    // check if User is author of at least one Post
    // Return true if he has, else false
    public function userHasPosts(int $userId): bool
    {
        $result = $this->dbConnect()->prepare('SELECT EXISTS (SELECT 1 from post WHERE post.user_id = :userId)');
        $result->bindValue(':userId', $userId, PDO::PARAM_INT);
        $result->execute();
        
        return boolval(current($result->fetch()));
    }

    // check if login already exists in Users
    // Return true it exists, else false
    public function userExists(string $login): bool
    {
        $result = $this->dbConnect()->prepare('SELECT EXISTS (SELECT 1 from user WHERE user.login = :userlogin)');
        $result->bindValue(':userlogin', $login, PDO::PARAM_STR);
        $result->execute();
        
        return boolval(current($result->fetch()));
    }

    // Create new User
    public function addUser(string $login, string $passwordHash, string $email): ?int
    {
        $conn = $this->dbConnect();
        $result = $conn->prepare('INSERT INTO user(user.login, user.password, user.email) VALUES (:userLogin, :userPassword, :userEmail)');
        $result->bindValue(':userLogin', $login, PDO::PARAM_STR);
        $result->bindValue(':userPassword', $passwordHash, PDO::PARAM_STR);
        $result->bindValue(':userEmail', $email, PDO::PARAM_STR);
        $result->execute();

        return (int)($conn->lastInsertId());
    }

    public function insertToken(string $token, int $newUserId): bool
    {
        $result = $this->dbConnect()->prepare('UPDATE user SET token = :token, last_update=NOW() WHERE user.id = :userId');
        $result->bindValue(':token', $token, PDO::PARAM_STR);
        $result->bindValue(':userId', $newUserId, PDO::PARAM_INT);

        return $result->execute();
    }

    public function searchToken(string $token): bool
    {
        $result = $this->dbConnect()->prepare('SELECT EXISTS (SELECT 1 from user WHERE user.token = :token)');
        $result->bindValue(':token', $token, PDO::PARAM_STR);
        $result->execute();
        
        return boolval(current($result->fetch()));
    }

    public function activateOneUser(string $token): bool
    {
        $result = $this->dbConnect()->prepare('UPDATE user SET token = NULL, activated = 1, last_update=NOW() WHERE user.token = :token');
        $result->bindValue(':token', $token, PDO::PARAM_STR);

        return $result->execute();
    }
}
