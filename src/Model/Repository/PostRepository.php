<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\Post;
use \App\Service\Database;
use \PDO;

class PostRepository extends Database
{
    // get Post with its id
    // Return Post
    public function getPost(int $postId): ?Post
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            WHERE post.id= :postId'
        );
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();
        $data = $result->fetch();
        if ($data === false) {
            return null;
        }
                
        return new Post($data);
    }
    
    // get last X Posts, sorted by most recent
    // return an array of Posts
    public function getMostXRecentPosts(int $postsNumberLimit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            ORDER BY post.created DESC LIMIT :postsNumberLimit'
        );
        $result->bindValue(':postsNumberLimit', $postsNumberLimit, PDO::PARAM_INT);
        $result->execute();

        return $result->fetchAll(PDO::FETCH_CLASS, Post::class);
    }

    // get last Posts, sorted by most recent, with limit and offset as parameters
    // return an array of Posts
    public function getPosts(int $offset, int $postsNumberLimit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            ORDER BY post.created DESC LIMIT :offset, :postsNumberLimit '
        );
        $result->bindValue(':offset', $offset, PDO::PARAM_INT);
        $result->bindValue(':postsNumberLimit', $postsNumberLimit, PDO::PARAM_INT);
        $result->execute();
        
        return $result->fetchAll(PDO::FETCH_CLASS, Post::class);
    }

    // get total number of Posts
    // return an int
    public function countPosts(): int
    {
        return (int)current($this->dbConnect()->query("SELECT COUNT(id) from post")->fetch());
    }

    // get next Post id, base on creation date, useful for pager
    // return an int
    public function getNextId(int $postId): ?int
    {
        $result = $this->dbConnect()->prepare(
            'SELECT id FROM post WHERE created = (
                SELECT MAX(created) FROM post WHERE id < :postId )'
        );
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return (int)$result->fetch()['id'];
        };
        return null;
    }

    // get previous Post id, base on creation date, useful for pager
    // return an int
    public function getPrevId(int $postId): ?int
    {
        $result = $this->dbConnect()->prepare(
            'SELECT id FROM post WHERE created = (
                SELECT MIN(created) FROM post WHERE id > :postId )'
        );
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return (int)$result->fetch()['id'];
        };
        return null;
    }

    // Update Post
    // return true if OK
    public function updatePost(int $postId, string $title, string $chapo, int $authorId, string $content): bool
    {
        $result = $this->dbConnect()->prepare('UPDATE post SET title = :title, chapo = :chapo, post.user_id = :authorId, content = :content, last_update=NOW() WHERE post.id = :postId');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->bindValue(':title', $title, PDO::PARAM_STR);
        $result->bindValue(':chapo', $chapo, PDO::PARAM_STR);
        $result->bindValue(':authorId', $authorId, PDO::PARAM_INT);
        $result->bindValue(':content', $content, PDO::PARAM_STR);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };

        return false;
    }

    // Add new Post
    // Return the ID of the newly created Post
    public function addPost(string $title, string $chapo, int $authorId, string $content): ?int
    {
        $conn = $this->dbConnect();
        $result = $conn->prepare('INSERT INTO post(title, chapo, content, post.user_id) VALUES (:title, :chapo, :content, :authorId)');
        $result->bindValue(':title', $title, PDO::PARAM_STR);
        $result->bindValue(':chapo', $chapo, PDO::PARAM_STR);
        $result->bindValue(':content', $content, PDO::PARAM_STR);
        $result->bindValue(':authorId', $authorId, PDO::PARAM_INT);
        $result->execute();

        return (int)($conn->lastInsertId());
    }

    // Delete a Post
    // return true if OK
    public function deleteOnePost(int $postId): bool
    {
        $result = $this->dbConnect()->prepare('DELETE FROM post WHERE post.id = :postId');
        $result->bindValue(':postId', $postId, PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return true;
        };
        
        return false;
    }
}
