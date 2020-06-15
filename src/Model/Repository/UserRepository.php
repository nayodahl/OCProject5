<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\User;
use App\Service\Database;

class UserRepository extends Database
{
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
