<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\Comment;
use \App\Service\Database;
use \PDO;

class CommentRepository extends Database
{
    // get all Comments from one Post with its id, with limit and offset as parameters
    // last parameter is the status of the comment, 1 for approved, 0 for not approved
    // return an array of Comments
    public function getCommentsFromPost(int $postId, int $offset, int $commentsNumberLimit, int $approved): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            WHERE comment.post_id= :postId
            AND comment.approved= :approved
            ORDER BY comment.created DESC LIMIT :offset, :commentsNumberLimit '
        );
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':commentsNumberLimit', $commentsNumberLimit, PDO::PARAM_INT);
        $result->bindValue(':approved', $approved, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_CLASS, '\App\Model\Entity\Comment');
    }

    // get not approved Comments, sorted by least recent, with limit and offset as parameters
    // return an array of Comments
    public function getAllNotApprovedComments(int $offset, int $commentsNumberLimit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            WHERE comment.approved= 0
            ORDER BY comment.created ASC LIMIT :offset, :commentsNumberLimit'
        );
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':commentsNumberLimit', $commentsNumberLimit, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_CLASS, '\App\Model\Entity\Comment');
    }

    // get total number of Comments
    // return an int
    public function countComments(int $approved): int
    {
        $result = $this->dbConnect()->prepare('SELECT COUNT(id) from comment WHERE approved = :approved');
        $result->bindValue(':approved', $approved, PDO::PARAM_INT);
        $result->execute();
        
        return (int)current($result->fetch());
    }

    public function countNumberOfApprovedCommentsFromPost(int $postId): int
    {
        $result = $this->dbConnect()->prepare('SELECT COUNT(id) FROM comment WHERE post_id= :postId AND approved= 1');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();

        return (int)current($result->fetch());
    }

    // write Comment in DB
    public function insertCommentToPost(int $postId, int $authorId, string $comment): bool
    {
        $result = $this->dbConnect()->prepare('INSERT INTO comment(post_id, comment.user_id, content) VALUES (:postId, :authorId, :comment)');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->bindValue(':authorId', $authorId, PDO::PARAM_INT);
        $result->bindValue(':comment', $comment, PDO::PARAM_STR);

        return $result->execute();
    }

    // approve Comment in DB
    public function setCommentToApproved(int $commentId): bool
    {
        $result = $this->dbConnect()->prepare('UPDATE comment SET approved = 1, last_update=NOW() WHERE id = :commentId');
        $result->bindValue(':commentId', $commentId, PDO::PARAM_INT);

        return $result->execute();
    }

    // refuse Comment in DB
    public function deleteComment(int $commentId): bool
    {
        $result = $this->dbConnect()->prepare('DELETE FROM comment WHERE id = :commentId');
        $result->bindValue(':commentId', $commentId, PDO::PARAM_INT);

        return $result->execute();
    }
}
