<?php
declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Comment;
use App\Service\Database;

class CommentRepository extends Database
{
    // get Comment with its id
    // Return Comment
    public function getComment(int $postId): Comment
    {
        // *********
        return new \App\Model\Entity\Comment($data);
    }

    // get all Comments from one Post
    // return an array of Comments
    public function getAllComments(int $postId): array
    {   
        $result = $this->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            WHERE comment.post_id= :postId'
        );
        $result->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $result->execute();
        $custom_array = [];

        while ($data = $result->fetch(\PDO::FETCH_ASSOC)) {
            array_push($custom_array, new Comment($data));
        }

        return $custom_array;
    }
}
