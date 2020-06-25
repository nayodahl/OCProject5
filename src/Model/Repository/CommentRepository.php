<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\Comment;
use \App\Service\Database;
use \PDO;

class CommentRepository extends Database
{
    // get all Comments from one Post with its id
    // second parameter is the status of the comment, 1 for approved, 0 for not approved
    // return an array of Comments
    public function getAllComments(int $postId, int $approved): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            WHERE comment.post_id= :postId
            AND comment.approved= :approved
            ORDER BY comment.created DESC'
        );
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->bindValue(':approved', $approved, PDO::PARAM_INT);
        $result->execute();
        $customArray = [];

        while ($data = $result->fetch()) {
            array_push($customArray, new Comment($data));
        }

        return $customArray;
    }

    public function getAllNotApprovedComments(): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            WHERE comment.approved= 0
            ORDER BY comment.created DESC'
        );
        $result->execute();
        $customArray = [];

        while ($data = $result->fetch()) {
            array_push($customArray, new Comment($data));
        }

        return $customArray;
    }
}
