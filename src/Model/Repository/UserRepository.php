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
            'SELECT id AS userID, user.login, user.password, user.email, user.type, user.token, DATE_FORMAT(user.created, \'%d/%m/%Y à %Hh%i\') AS created 
            FROM user 
            WHERE user.type <> "superadmin"
            ORDER BY user.login ASC LIMIT :offset, :usersNumberLimit '
        );
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':usersNumberLimit', $limit, PDO::PARAM_INT);
        $result->execute();
        $customArray = [];

        while ($data = $result->fetch()) {
            array_push($customArray, new User($data));
        }
        return $customArray;
    }

    /*
    public function getSingleUser(int $userId): array
    {
    $result = $this->dbConnect()->prepare(
        'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin
        FROM comment
        INNER JOIN user ON comment.user_id = user.id
        WHERE user.user_id= :userId
        AND comment.approved= :approved'
    );
    $result->bindValue(':userId', $userId, \PDO::PARAM_INT);
    $result->execute();
    $custom_array = [];

    while ($data = $result->fetch(\PDO::FETCH_ASSOC)) {
        array_push($custom_array, new Comment($data));
    }

    return $custom_array;
    }
    */
}
