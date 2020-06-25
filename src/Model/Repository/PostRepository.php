<?php
declare(strict_types=1);

namespace App\Model\Repository;

use \App\Model\Entity\Post;
use \App\Service\Database;

class PostRepository extends Database
{
    // get Post with its id
    // Return Post
    public function getPost(int $postId): Post
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            WHERE post.id= :postId'
        );
        $result->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $result->execute();
                
        return new Post($result->fetch());
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
        $result->bindValue(':postsNumberLimit', $postsNumberLimit, \PDO::PARAM_INT);
        $result->execute();
        $customArray = [];

        while ($data = $result->fetch()) {
            array_push($customArray, new Post($data));
        }
        return $customArray;
    }

    // get last X Posts, sorted by most recent, with limit and offset as parameters
    // return an array of Posts
    public function getPosts(int $offset, int $postsNumberLimit): array
    {
        $result = $this->dbConnect()->prepare(
            'SELECT post.id AS postId, post.title, post.chapo, post.content, DATE_FORMAT(post.created, \'%d/%m/%Y à %Hh%i\') AS created, DATE_FORMAT(post.last_update, \'%d/%m/%Y à %Hh%i\') AS lastUpdate, post.user_id AS authorId, user.login AS authorLogin 
            FROM post 
            INNER JOIN user ON post.user_id = user.id
            ORDER BY post.created DESC LIMIT :offset, :postsNumberLimit '
        );
        $result->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $result->bindValue(':postsNumberLimit', $postsNumberLimit, \PDO::PARAM_INT);
        $result->execute();
        $customArray = [];

        while ($data = $result->fetch()) {
            array_push($customArray, new Post($data));
        }
        return $customArray;
    }

    // get total number of Posts
    // return an int
    public function countPosts()
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
        $result->bindValue(':postId', $postId, \PDO::PARAM_INT);
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
        $result->bindValue(':postId', $postId, \PDO::PARAM_INT);
        $result->execute();
        if ($result->rowCount() > 0) {
            return (int)$result->fetch()['id'];
        };
        return null;
    }
}
