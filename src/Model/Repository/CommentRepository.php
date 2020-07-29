<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\Comment;
use \App\Service\Database;
use \PDO;

class CommentRepository
{
    private $database;
    
    public function __construct(Database $database)
    {
        $this->database = $database;
    }
    
    // get all Comments from one Post with its id, with limit and offset as parameters
    // last parameter is the status of the comment, 1 for approved, 0 for not approved
    // return an array of Comments
    public function getCommentsFromPost(int $postId, int $offset, int $commentsNumberLimit, int $approved): array
    {
        $result = $this->database->dbConnect()->prepare(
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

        return $result->fetchAll(PDO::FETCH_CLASS, Comment::class);
    }

    // get not approved Comments, sorted by least recent, with limit and offset as parameters
    // return an array of Comments
    public function getAllNotApprovedComments(int $offset, int $commentsNumberLimit): array
    {
        $result = $this->database->dbConnect()->prepare(
            'SELECT comment.id AS commentId, comment.content, DATE_FORMAT(comment.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(comment.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, comment.post_id AS postId, comment.user_id AS authorId, user.login AS authorLogin, post.title AS postTitle 
            FROM comment 
            INNER JOIN user ON comment.user_id = user.id
            INNER JOIN post ON comment.post_id = post.id
            WHERE comment.approved= 0
            ORDER BY comment.created ASC LIMIT :offset, :commentsNumberLimit'
        );
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':commentsNumberLimit', $commentsNumberLimit, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_CLASS, Comment::class);
    }

    // get total number of Comments with status as parameter, 1 is for approved, 0 for not approved
    // return an int
    public function countComments(int $approved): int
    {
        $result = $this->database->dbConnect()->prepare('SELECT COUNT(id) from comment WHERE approved = :approved');
        $result->bindValue(':approved', $approved, PDO::PARAM_INT);
        $result->execute();
        
        return (int)current($result->fetch());
    }

    // count number of approved Comments from a Post
    // return an int
    public function countNumberOfApprovedCommentsFromPost(int $postId): int
    {
        $result = $this->database->dbConnect()->prepare('SELECT COUNT(id) FROM comment WHERE post_id= :postId AND approved= 1');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();

        return (int)current($result->fetch());
    }

    // Insert Comment in DB
    // return true if OK
    public function insertCommentToPost(int $postId, int $authorId, string $comment): bool
    {
        $result = $this->database->dbConnect()->prepare('INSERT INTO comment(post_id, comment.user_id, content) VALUES (:postId, :authorId, :comment)');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->bindValue(':authorId', $authorId, PDO::PARAM_INT);
        $result->bindValue(':comment', $comment, PDO::PARAM_STR);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };
        
        return false;
    }

    // Update "approved" attribute of a Comment in DB
    // return true if OK
    public function setCommentToApproved(int $commentId): bool
    {
        $result = $this->database->dbConnect()->prepare('UPDATE comment SET approved = 1, last_update=NOW() WHERE id = :commentId');
        $result->bindValue(':commentId', $commentId, PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };
        return false;
    }

    // refuse Comment in DB, so deleting it
    // return true if OK
    public function deleteComment(int $commentId): bool
    {
        $result = $this->database->dbConnect()->prepare('DELETE FROM comment WHERE id = :commentId');
        $result->bindValue(':commentId', $commentId, PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };
        return false;
    }
}
